<?php

class Mage_Adminhtml_Model_System_Config_Backend_Shipping_Sto extends Mage_Core_Model_Config_Data
{
	public function _afterSave()
	{
		Mage::getResourceModel('shipping/carrier_sto')->uploadAndImport($this);
	}
}
