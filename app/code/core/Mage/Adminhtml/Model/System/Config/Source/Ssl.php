<?php

class Mage_Adminhtml_Model_System_Config_Source_Ssl
{
    public function toOptionArray ()
    {
        return array(
            array('value' => 0, 'label' => Mage::helper('adminhtml')->__('No')),
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('Yes(tls)')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('Yes(ssl)'))
        );
    }
}