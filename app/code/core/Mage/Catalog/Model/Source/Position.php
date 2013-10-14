<?php

class Mage_Catalog_Model_Source_Position
{
	public static function toOptionArray()
	{
		$list = array(
					"left" => Mage::Helper('catalog')->__('left'),
					"right" => Mage::Helper('catalog')->__('right'),
					"top" => Mage::Helper('catalog')->__('top'),
                    "bottom" => Mage::Helper('catalog')->__('bottom'),
                    "inside" => Mage::Helper('catalog')->__('inside'),
					);

		return ($list);
	}
}