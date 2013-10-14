<?php

class Makingware_Alipay_PaymentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Order instance
     */
    protected $_order;

    /**
     * Get order  获得定单
     *
     * @param  none
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
     * 支付入口
     */
    public function redirectAction ()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setAlipayPaymentQuoteId($session->getQuoteId());
        $order = $this->getOrder();

        if (! $order->getId()) {
            $this->norouteAction();
            return;
        }
        
        try {
        	$order->addStatusToHistory(
            	$order->getStatus(),
           		Mage::helper('alipay')->__('Customer will be redirected to apipay payment page')
        	);

        	$order->save();
        	$session->unsQuoteId();
        	return $this->getResponse()->setBody(
        		$this->getLayout()
            		->createBlock('alipay/redirect')
            		->setOrder($order)
            		->toHtml()
        	);
        }catch (Exception $e) {
        	Mage::getSingleton('core/session')->addError($e->getMessage());
        	Mage::logException($e);
        }
        $this->_redirect('sales/order/view', array('order_id' => $order->getId()));
    }

    /**
     * Alipay response router
     *
     * @param none
     * @return void
     */
    public function notifyAction ()
    {
//        $file = dirname(__FILE__);
//        $file = realpath($file);
//        $file .= DS;
//        $file .= 'alipay-'.date('Ymd').'.log';
//
//        error_log(var_export($this->getRequest()->getParams(), true), 3, $file);

        $model = Mage::getModel('alipay/payment');

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

        if (empty($postData['body']) || empty($postData['trade_status'])) {
            $model->generateErrorResponse();
        }

        $PAYMENTLOG = array(
            'Tag' => 'Alipay',
            'AlipayReturn' => $postData,
            'PaymentRecord' => false
        );

        $notifyModel = Mage::getModel('alipay/notify');
        $paymentModel = Mage::getModel('alipay/payment');
        $partner = $paymentModel->getConfigData('partner_id');
        $security_code = $paymentModel->getConfigData('security_code');
        $input_charset = 'utf-8';
        $sign_type = "MD5";
        $transport = $paymentModel->getConfigData('transport');

        if (empty($transport)) {
            $transport = 'http';
        }

        $notifyModel->alipay_notify($partner, $security_code, $sign_type,
        $input_charset, $transport); //构造通知函数信息
        $verify_result = $notifyModel->notify_verify(); //计算得出通知验证结果

        if (! $verify_result) {
            echo 'fail';
            return;
        }

        $this->logvarRS('notifyAction', $_POST, 'pending_paypal', Mage::helper('alipay')->__('Payment accepted by Alipay'));
        $order = Mage::getModel('sales/order')->loadByIncrementId($postData['body']);

        if (! $order->getId()) {
            $model->generateErrorResponse();
        }

        switch ($postData['trade_status']) {
            case 'TRADE_SUCCESS':
            case 'WAIT_SELLER_SEND_GOODS':
                if ($order->getStatus() == 'pending') {
                    Mage::log('修改订单发货状态');
                    $order->setData('state', Mage_Sales_Model_Order::STATE_PROCESSING);
                    $order->addStatusToHistory(
                        Mage_Sales_Model_Order::STATE_PROCESSING,
                        Mage::helper('alipay')->__('买家已付款，等待卖家发货')
                    );
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
                }
                break;
            case 'WAIT_BUYER_PAY':
            case 'SEND_GOODS':
            case 'WAIT_BUYER_CONFIRM_GOODS':
            case 'TRADE_FINISHED':
                break;
        }

        $message = sprintf("\n--- BEGIN ---\n%s\n--- END ---\n", var_export($PAYMENTLOG, true));
        Mage::log($message, Zend_Log::DEBUG, 'payment.log', true);

        echo 'success';
    }

    /**
     * Save invoice for order
     *
     * @param Mage_Sales_Model_Order $order
     * @return boolean Can save invoice or not
     */
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
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getAlipayPaymentQuoteId());
        $session->unsAlipayPaymentQuoteId();
        $order = $this->getOrder();

        if (! $order->getId()) {
            $this->norouteAction();
            return;
        }

        $order->addStatusToHistory($order->getStatus(),
        Mage::helper('alipay')->__('Customer successfully returned from Alipay'));
        $order->save();
        $this->_redirect('customer/account/index');
    }

    public function normalAction ()
    {
//        $file = dirname(__FILE__);
//        $file = realpath($file);
//        $file .= DS;
//        $file .= 'alipay-'.date('Ymd').'.log';
//
//        error_log(var_export($this->getRequest()->getParams(), true), 3, $file);

        $model = Mage::getModel('alipay/payment');

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

        if (empty($postData['body']) || empty($postData['trade_status'])) {
            $model->generateErrorResponse();
        }

        $PAYMENTLOG = array(
            'Tag' => 'Alipay',
            'AlipayReturn' => $postData,
            'PaymentRecord' => false
        );

        $notifyModel = Mage::getModel('alipay/notify');
        $paymentModel = Mage::getModel('alipay/payment');
        $partner = $paymentModel->getConfigData('partner_id');
        $security_code = $paymentModel->getConfigData('security_code');
        $input_charset = 'utf-8';
        $sign_type = "MD5";
        $transport = $paymentModel->getConfigData('transport');

        if (empty($transport)) {
            $transport = 'http';
        }

        $notifyModel->alipay_notify($partner, $security_code, $sign_type,
        $input_charset, $transport); //构造通知函数信息
        $verify_result = $notifyModel->return_verify(); //计算得出通知验证结果

        if (! $verify_result) {
            $model->generateErrorResponse();
        }

        $this->logvarRS('notifyAction', $_POST, 'pending_paypal', Mage::helper('alipay')->__('Payment accepted by Alipay'));
        $order = Mage::getModel('sales/order')->loadByIncrementId($postData['body']);

        if (! $order->getId()) {
            $model->generateErrorResponse();
        }

        switch ($postData['trade_status']) {
            case 'TRADE_SUCCESS':
            case 'WAIT_SELLER_SEND_GOODS':
                if ($order->getStatus() == 'pending') {
                    Mage::log('修改订单发货状态');
                    $order->setData('state', Mage_Sales_Model_Order::STATE_PROCESSING);
                    $order->addStatusToHistory(
                        Mage_Sales_Model_Order::STATE_PROCESSING,
                        Mage::helper('alipay')->__('买家已付款，等待卖家发货')
                    );
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
                }
                break;
            case 'WAIT_BUYER_PAY':
            case 'SEND_GOODS':
            case 'WAIT_BUYER_CONFIRM_GOODS':
            case 'TRADE_FINISHED':
                break;
        }

        $message = sprintf("\n--- BEGIN ---\n%s\n--- END ---\n", var_export($PAYMENTLOG, true));
        Mage::log($message, Zend_Log::DEBUG, 'payment.log', true);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function loginAction ()
    {
        $alipaylogin = Mage::getStoreConfig('payment/alipay/alipaylogin');

        if ($alipaylogin == 1) {
            $this->getResponse()->setBody(
                $this->getLayout()
                    ->createBlock('alipay/login')
                    ->toHtml()
            );
        } else {
            $this->_redirect('customer/account/login');
        }
    }

    public function backAction ()
    {
        $notify = Mage::getModel('alipay/notify');
        $verify_result = $notify->confirm($_GET);
        $_GET['email'] = 'alipay_' . $_GET['email'];

        if ($verify_result) {
            if ($this->checkVipExist($_GET['email'])) {
                $this->getResponse()->setBody(
                    $this->getLayout()
                        ->createBlock('alipay/loginbk')
                        ->toHtml()
                );
            } else {
                $this->getResponse()->setBody(
                    $this->getLayout()
                        ->createBlock('alipay/loginpost')
                        ->toHtml()
                );
            }
        } else {
            $this->_redirect('/');
        }
    }

    public function checkVipExist ($email)
    {
        $flag = true;
        $collection = Mage::getResourceModel('customer/customer_collection')->addAttributeToSelect('email');
        $collection->load();

        foreach ($collection as $_customer) {
            if ($_customer['email'] == $email) {
                $flag = false;
                break;
            }
        }

        return $flag;
    }

    /**
     * Failure payment page
     *
     * @param none
     * @return void
     */
    public function errorAction ()
    {
        $session = Mage::getSingleton('checkout/session');
        $errorMsg = Mage::helper('alipay')->__('There was an error occurred during paying process.');
        $order = $this->getOrder();

        if (! $order->getId()) {
            $this->norouteAction();
            return;
        }

        if ($order instanceof Mage_Sales_Model_Order && $order->getId()) {
            $order->addStatusToHistory(
                Mage_Sales_Model_Order::STATE_CANCELED,
                Mage::helper('alipay')->__('Customer returned from Alipay.') . $errorMsg
            );
            $order->save();
        }

        $this->loadLayout();
        $this->renderLayout();
        Mage::getSingleton('checkout/session')->unsLastRealOrderId();
    }

    /**
     * 接口调用记录
     *
     * 使用时注意日志存放位置(****)
     *
     * @param String $function_name(调用方法名)
     * @param String $postData(调用或返回数据)
     * @param String $inout(操作方式)
     * @param String $msg(异常信息)
     *
     * @access public
     */
    public function logvarRS ($function_name, $postData, $inout, $msg = '')
    {
        $content = array('function_name' => $function_name,
        $inout => $postData, 'msg' => $msg);
        $_path = dirname(__FILE__) . DS;
        ini_set('date.timezone', 'PRC');
        file_put_contents($_path . date('Ymd') . '.txt',
        date('Y-m-d H:i:s') . "\n" . var_export($content, true) . "\n" . "\n", FILE_APPEND);
    }
}
