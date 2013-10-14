<?php

class Makingware_EnhancedProductManager_Model_System_Config_Source_Columns_Show
{
    public function toOptionArray ()
    {
        $collection = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter(
            Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId())
            ->addFilter("is_visible", 1);
            
        $cols = array();
        $cols[] = array('value' => 'id', 'label' => 'ID');
        $cols[] = array('value' => 'type_id', 
        'label' => 'Type (simple, bundle, etc)');
        $cols[] = array('value' => 'attribute_set_id', 
        'label' => 'Attribute Set');
        $cols[] = array('value' => 'qty', 'label' => 'Quantity');
        $cols[] = array('value' => 'websites', 'label' => 'Websites');
        
        foreach ($collection->getItems() as $col) {
            $cols[] = array('value' => $col->getAttributeCode(), 
            'label' => $col->getFrontendLabel());
        }
        
        return $cols;
    }
}