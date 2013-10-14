<?php

class Makingware_Bill_Block_Onepage_Bill extends Mage_Checkout_Block_Onepage_Abstract
{
    protected function _construct ()
    {
        $this->getCheckout()->setStepData('bill', array(
        	'label' => Mage::helper('checkout')->__('Bill Information'), 
            'is_show' => $this->isShow()
        ));
        
        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('bill', 'allow', true);
        }
        
        parent::_construct();
    }
}