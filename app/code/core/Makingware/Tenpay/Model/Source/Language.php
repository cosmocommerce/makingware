<?php

class Makingware_Tenpay_Model_Source_Language
{
    public function toOptionArray ()
    {
        return array(
            array('value' => 'EN', 'label' => Mage::helper('tenpay')->__('English')), 
            array('value' => 'FR', 'label' => Mage::helper('tenpay')->__('French')), 
            array('value' => 'DE', 'label' => Mage::helper('tenpay')->__('German')), 
            array('value' => 'IT', 'label' => Mage::helper('tenpay')->__('Italian')), 
            array('value' => 'ES', 'label' => Mage::helper('tenpay')->__('Spain')), 
            array('value' => 'NL', 'label' => Mage::helper('tenpay')->__('Dutch'))
        );
    }
}



