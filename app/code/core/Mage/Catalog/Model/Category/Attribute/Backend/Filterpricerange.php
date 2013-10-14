<?php
class Mage_Catalog_Model_Category_Attribute_Backend_Filterpricerange extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
	public function beforeSave($object)
    {
    	$attrCode = $this->getAttribute()->getAttributeCode();
    	if (is_null($object->getData($attrCode))) {
    		$object->setData($attrCode, $this->getDefaultValue());
    	}
    	return parent::beforeSave($object);
    }
}