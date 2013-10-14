<?php

class Makingware_SearchOrder_OrderController extends Mage_Core_Controller_Front_Action
{
    public function indexAction ()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function orderAction ()
    {
        $order_id = $this->getRequest()->getParam('order_id');
        $shipping_name = $this->getRequest()->getParam('shipping_name');

        if (!empty( $order_id) && !empty($shipping_name)) {
            
            $session = Mage::getSingleton('core/session');
            $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
            $order_address = Mage::getModel('sales/order_address')->load($order->getShippingAddressId());
            
            if (! $order->getId()) {
                $session->addError($this->__("Order ID don't exist"));
                $this->_redirect('*/*/index');
            } elseif ($order_address->getName() != $shipping_name) {
                $session->addError($this->__("收件人不正确"));
                $this->_redirect('*/*/index');
            } else {
                Mage::register('current_order', $order);
                $this->loadLayout();
                $this->renderLayout();
            }
            
        }else{
			$session = Mage::getSingleton('core/session');
            if(empty($order_id))
			    $session->addError($this->__("Order ID can't be empty."));
            if(empty($shipping_name))
                $session->addError($this->__("收件人不能为空"));
			$this->_redirect('*/*/index');
        }
    }
}
