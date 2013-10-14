<?php

class Makingware_Alipay_Model_Source_Transport
{
    public function toOptionArray()
    {
        return array(
            array(
            	'value' => 'https',
            	'label' => Mage::helper('alipay')->__('https')
            ),
            array(
            	'value' => 'http',
            	'label' => Mage::helper('alipay')->__('http')
            ),
        );
    }
}