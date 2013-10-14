<?php

class Mage_Adminhtml_Model_System_Config_Source_Agent
{
    public function toOptionArray ()
    {
        return array(
            array('value' => '0', 'label' => Mage::helper('adminhtml')->__('Disable Email')) ,
            array('value' => '1', 'label' => Mage::helper('adminhtml')->__('Local Email Service')),
            array('value' => '2', 'label' => Mage::helper('adminhtml')->__('Smtp Service'))
        );
    }
}