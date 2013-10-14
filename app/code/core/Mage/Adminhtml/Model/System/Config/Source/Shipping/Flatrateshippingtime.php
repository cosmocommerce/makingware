<?php
class Mage_Adminhtml_Model_System_Config_Source_Shipping_Flatrateshippingtime
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'workday', 'label'=> Mage::helper('adminhtml')->__('Only workday can deliver goods')),
            array('value'=>'anytime', 'label'=>Mage::helper('adminhtml')->__('Anytime can deliver goods')),
            array('value'=>'weekend', 'label'=>Mage::helper('adminhtml')->__('Only weekend can deliver goods')),
        );
    }
}
