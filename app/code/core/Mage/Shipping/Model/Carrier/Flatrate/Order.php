<?php
class Mage_Shipping_Model_Carrier_Flatrate_Order extends Mage_Core_Model_Abstract
{
	protected $_quote;
	protected $_order;

    protected function _construct ()
    {
         $this->_init('shipping/carrier_flatrate_order');
    }

    public function getQuote()
    {
    	if (empty($this->_quote)) {
    		$this->_quote = Mage::getSingleton('shipping/carrier_flatrate_quote');
    		if ($id = $this->_quote->getQuote()->getId()) {
    			$this->_quote->load($id);
    		}
    	}
    	return $this->_quote;
    }

    public function setOrder(Mage_Sales_Model_Order $order)
    {
    	$this->_order = $order;
    	if ($id = $order->getId()) {
    		$this->_order->load($id);
    	}
    	return $this;
    }

    public function getOrder()
    {
    	if (empty($this->_order)) {
    		$this->setOrder(Mage::getModel('sales/order')->setId(Mage::getSingleton('checkout/session')->getLastOrderId()));
    	}
    	return $this->_order;
    }

    public function saveOrder()
    {
    	$data = $this->getQuote()->getData();
		$data['order_id'] = $this->getOrder()->getId();
		$this->setData($data)->save();

    	return $this;
    }
}
