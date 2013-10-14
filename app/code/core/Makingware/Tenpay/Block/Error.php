<?php

class Makingware_Tenpay_Block_Error extends Mage_Core_Block_Template
{
    public function __construct ()
    {
        parent::__construct();
        $this->setTemplate('tenpay/error.phtml');
    }
}