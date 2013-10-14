<?php

class Makingware_UrlKeyMaker_Model_System_Config_Source_Type
{
	public static function toOptionArray()
	{
		$list = array(
            'sku' => Mage::Helper('catalog')->__('SKU'),
            'pinyin' => Mage::Helper('makingware_urlkeymaker')->__('Pinyin from Product Name'),
        );

		return $list;
	}
}