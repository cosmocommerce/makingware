<?php

class Makingware_Tenpay_Block_Result extends Mage_Core_Block_Template
{
    public function __construct ()
    {
        parent::__construct();
        $this->setTemplate('tenpay/result.phtml');
    }
    public function getShowUrl ()
    {
        if (Mage::registry('success')) {
            return Mage::getUrl('tenpay/payment/success');
        } else {
            return Mage::getUrl('tenpay/payment/error');
        }
    }
}