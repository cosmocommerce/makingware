<?php

class Makingware_EnhancedProductManager_Model_System_Config_Source_Datasource_Datasource
{
    public function toOptionArray ()
    {
        $datas = array();
        $datas[] = array('value' => 'url', 
        	'label' => Mage::helper('makingware_enhancedproductmanager')->__(
        	'From URL'));
        
        $datas[] = array('value' => 'file', 
        	'label' => Mage::helper('makingware_enhancedproductmanager')->__(
        	'From File')
        );
        
        return $datas;
    }
}