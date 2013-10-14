<?php
class Mage_Adminhtml_Model_System_Config_Source_Shipping_Sf
{
	public function toOptionArray()
	{
		$tableRate = Mage::getSingleton('shipping/carrier_sf');
		$arr = array();
		foreach ($tableRate->getCode('condition_name') as $k=>$v) {
			$arr[] = array('value'=>$k, 'label'=>$v);
		}
		return $arr;
	}
}
