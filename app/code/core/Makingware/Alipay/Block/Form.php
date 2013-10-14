<?php

class Makingware_Alipay_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct ()
    {
        $this->setTemplate('alipay/form.phtml');
        parent::_construct();
    }
}