<?php

class Makingware_EnhancedProductManager_Model_System_Config_Source_Sort_Direction
{
    public function toOptionArray ()
    {
        $sorts = array();
        $sorts[] = array('value' => 'desc', 
        	'label' => Mage::helper('makingware_enhancedproductmanager')->__('Descending')
        );
        
        $sorts[] = array('value' => 'asc', 
            'label' => Mage::helper('makingware_enhancedproductmanager')->__('Ascending')
        );
        
        return $sorts;
    }
}