<?php

class Mage_Customer_Model_Entity_Address_Attribute_Source_Area extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
	public function getAllOptions()
	{
		if (!$this->_options) {
			$this->_options = Mage::getResourceModel('directory/area_collection')->load()->toOptionArray();
		}
		return $this->_options;
	}
}
