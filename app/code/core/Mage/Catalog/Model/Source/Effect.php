<?php

class Mage_Catalog_Model_Source_Effect
{
	public static function toOptionArray()
	{
		$list = array(
					"none" => Mage::Helper('catalog')->__('None'),
					"tint" => Mage::Helper('catalog')->__('Tint'),
					"focus" => Mage::Helper('catalog')->__('Soft Focus')
					);

		return ($list);
	}
}