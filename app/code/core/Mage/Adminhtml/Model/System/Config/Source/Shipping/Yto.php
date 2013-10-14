<?php
class Mage_Adminhtml_Model_System_Config_Source_Shipping_Yto
{
	public function toOptionArray()
	{
		$tableRate = Mage::getSingleton('shipping/carrier_yto');
		$arr = array();
		foreach ($tableRate->getCode('condition_name') as $k=>$v) {
			$arr[] = array('value'=>$k, 'label'=>$v);
		}
		return $arr;
	}
}
