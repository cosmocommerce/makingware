<?php
class Mage_Adminhtml_Model_System_Config_Source_Shipping_Ems
{
	public function toOptionArray()
	{
		$tableRate = Mage::getSingleton('shipping/carrier_ems');
		$arr = array();
		foreach ($tableRate->getCode('condition_name') as $k=>$v) {
			$arr[] = array('value'=>$k, 'label'=>$v);
		}
		return $arr;
	}
}
