<?php

class Makingware_Tenpay_PaymentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Order instance
     */
    protected $_order;
    /**
     * Get order
     *
     * @param none
     * @return Mage_Sales_Model_Order
     */
	public function getOrder ()
    {
        if ($this->_order == null) {
            $order_id = $this->getRequest()->getParam('order_id');

            if (empty($order_id)) {
                $session = Mage::getSingleton('checkout/session');
                $this->_order = Mage::getModel('sales/order');
                $this->_order->loadByIncrementId($session->getLastRealOrderId());
            } else {
                $this->_order = Mage::getModel('sales/order');
                $this->_order->loadByIncrementId($order_id);
            }
        }

        return $this->_order;
    }

    /**
     * When a customer chooses Alipay on Checkout/Payment page
     *
     */
    public function redirectAction ()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setTenpayPaymentQuoteId($session->getQuoteId());
        $order = $this->getOrder();

        if (! $order->getId()) {
            $this->norouteAction();
            return;
        }
        
        try {
        	$order->addStatusToHistory(
        		$order->getStatus(),
	        	Mage::helper('tenpay')->__('Customer was redirected to Tenpay')
        	);
	        $order->save();
	        $session->unsQuoteId();
	        return $this->getResponse()->setBody(
	        	$this->getLayout()
	            	->createBlock('tenpay/redirect')
	            	->setOrder($order)
	            	->toHtml()
	        );
        }catch (Exception $e) {
        	Mage::getSingleton('core/session')->addError($e->getMessage());
        	Mage::logException($e);
        }
        $this->_redirect('sales/order/view', array('order_id' => $order->getId()));
    }

    public function notifyAction ()
    {
        $model = Mage::getModel('tenpay/payment');

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $method = 'post';
        } else {
            if ($this->getRequest()->isGet()) {
                $postData = $this->getRequest()->getQuery();
                $method = 'get';
            } else {
                $model->generateErrorResponse();
            }
        }

        $PAYMENTLOG = array(
            'Tag' => 'Tenpay',
            'TenpayReturn' => $postData,
            'PaymentRecord' => false
        );

        $model = Mage::getSingleton('tenpay/payment');
        $cmdno = $postData['cmdno'];
        $pay_result = $postData['pay_result']; //0 is success
        $pay_info = $postData['pay_info']; //empty when it's success
        $date = $postData['date']; //SP's date
        $bargainor_id = $postData['bargainor_id']; //SP's SPID
        $transaction_id = $postData['transaction_id']; //tenpay's orderid
        $billno = $postData['sp_billno']; //SP's internal order id.
        $total_fee = $postData['total_fee']; //grant total price,fen as unit
        $fee_type = $postData['fee_type']; //currency code
        $attach = isset($postData['attach']) ? $postData['attach'] : ''; //SP
        $spbill_create_ip = isset($postData['spbill_create_ip']) ? $postData['spbill_create_ip'] : '';
        $sign = $postData['sign']; //md5 sign
        $security_code = $model->getConfigData('security_code');
        $presignstr = "cmdno=1" . "&pay_result=" . $pay_result . "&date=" . $date .
         "&transaction_id=" . $transaction_id . "&sp_billno=" . $billno .
         "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&attach=" .
         $attach . "&key=" . $security_code;
        $myMD5sign = strtoupper(md5($presignstr));
        $succ = $pay_result;
        $msg = $pay_info; //the message returned by back
        $order = Mage::getModel('sales/order')->loadByIncrementId($postData['sp_billno']);

        if (! $order->getId()) {
            $model->generateErrorResponse();
        }

        if ($succ == 0) {
            if ($myMD5sign == $sign) {
                $order->setData('state', Mage_Sales_Model_Order::STATE_PROCESSING);
                $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_PROCESSING,Mage::helper('tenpay')->__('Payment accepted by Tenpay'));
                $order->save();

                if ($order->canInvoice())
                {
                    $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                    $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                    $invoice->register();

                    Mage::getModel('core/resource_transaction')
                            ->addObject($invoice)
                            ->addObject($invoice->getOrder())
                            ->save();

                    $PAYMENTLOG['PaymentRecord'] = true;
                }

                Mage::register('success', 1);
            } else {
                Mage::register('success', 0);
            }
        } else {
            Mage::register('success', 0);
        }

        $message = sprintf("\n--- BEGIN ---\n%s\n--- END ---\n", var_export($PAYMENTLOG, true));
        Mage::log($message, Zend_Log::DEBUG, 'payment.log', true);

        $this->loadLayout();
        $this->getLayout()
            ->getBlock('content')
            ->append(
           $this->getLayout()
           ->createBlock('tenpay/result', 'result')
            );

        $this->renderLayout();
    }

    protected function saveInvoice (Mage_Sales_Model_Order $order)
    {
        if ($order->canInvoice()) {
            $convertor = Mage::getModel('sales/convert_order');
            $invoice = $convertor->toInvoice($order);
            foreach ($order->getAllItems() as $orderItem) {
                if (! $orderItem->getQtyToInvoice()) {
                    continue;
                }
                $item = $convertor->itemToInvoiceItem($orderItem);
                $item->setQty($orderItem->getQtyToInvoice());
                $invoice->addItem($item);
            }

            $invoice->collectTotals();
            $invoice->register()->capture();
            Mage::getModel('core/resource_transaction')->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save();

            return true;
        }

        return false;
    }

    /**
     * Success payment page
     *
     * @param none
     * @return void
     */
    public function successAction ()
    {
        $this->loadLayout();
        $this->getLayout()
            ->getBlock('content')
            ->append(
            $this->getLayout()
            ->createBlock('tenpay/success', 'success')
            );
        $this->renderLayout();
    }

    /**
     * Failure payment page
     *
     * @param none
     * @return void
     */
    public function errorAction ()
    {
        $this->loadLayout();
        $this->getLayout()
            ->getBlock('content')
            ->append(
            $this->getLayout()
            ->createBlock('tenpay/error', 'error')
            );
        $this->renderLayout();
    }

    public function sign ($prestr)
    {
        $mysign = md5($prestr);

        return $mysign;
    }

    public function para_filter ($parameter)
    {
        $para = array();

        while (list ($key, $val) = each($parameter)) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $para[$key] = $parameter[$key];
            }
        }

        return $para;
    }

    public function arg_sort ($array)
    {
        ksort($array);
        reset($array);

        return $array;
    }

    public function charset_encode ($input, $_output_charset, $_input_charset = "GBK")
    {
        $output = "";

        if ($_input_charset == $_output_charset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists("iconv")) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else {
            die("sorry, you have no libs support for charset change.");
        }

        return $output;
    }
}
