<?php
class Makingware_Tenpay_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct ()
    {
        $this->setTemplate('tenpay/form.phtml');
        parent::_construct();
    }
    
    protected function isNetsilverLeague()
    {
    	return Mage::getStoreConfigFlag('payment/tenpay/netsilver_league');
    }
}