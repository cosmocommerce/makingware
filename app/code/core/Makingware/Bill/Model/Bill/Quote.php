<?php
class Makingware_Bill_Model_Bill_Quote extends Mage_Core_Model_Abstract
{
	protected $_quote;
	
    protected function _construct ()
    {
        $this->_init('makingware_bill/bill_quote');
    }
    
    public function setQuote(Mage_Sales_Model_Quote $quote)
    {
    	$this->_quote = $quote;
    	if ($id = $quote->getId()) {
    		$this->load($id);
    	}
    	return $this;
    }
    
    public function getQuote()
    {
    	if (empty($this->_quote)) {
    		$this->setQuote(Mage::getSingleton('checkout/session')->getQuote());
    	}
    	return $this->_quote;
    }
}
