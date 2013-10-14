<?php
class Makingware_Bill_Model_Bill extends Mage_Core_Model_Abstract
{
	protected $_billQuote;
	protected $_order;
	
    protected function _construct ()
    {
        $this->_init('makingware_bill/bill');
    }
    
    public function getBillQuote()
    {
    	if (empty($this->_billQuote)) {
    		$this->_billQuote = Mage::getSingleton('makingware_bill/bill_quote');
    		if ($id = $this->_billQuote->getQuote()->getId()) {
    			$this->_billQuote->load($id);
    		}
    	}
    	return $this->_billQuote;
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
    
    public function saveBill()
    {
    	$data = $this->getBillQuote()->getData();
		
		$data['order_id'] = $this->getOrder()->getId();
		$data['increment_id'] = $this->getOrder()->getIncrementId();
		$data['price'] = $this->getOrder()->getSubtotal();
		$this->setData($data)->save();
    	return $this;
    }
}
