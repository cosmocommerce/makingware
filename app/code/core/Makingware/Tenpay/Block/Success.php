<?php

class Makingware_Tenpay_Block_Success extends Mage_Core_Block_Template
{
    public function __construct ()
    {
        parent::__construct();
        $this->setTemplate('tenpay/success.phtml');
    }
}