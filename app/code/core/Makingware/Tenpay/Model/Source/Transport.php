<?php
class Makingware_Tenpay_Model_Source_Transport
{
    public function toOptionArray ()
    {
        return array(
            array('value' => 'https', 'label' => Mage::helper('tenpay')->__('https')), 
            array('value' => 'http', 'label' => Mage::helper('tenpay')->__('http'))
            );
    }
}