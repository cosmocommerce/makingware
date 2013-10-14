<?php

class Mage_Catalog_Model_Source_Fixed
{
	public static function toOptionArray()
	{
		$list = array(
                    "auto" => Mage::Helper('catalog')->__('Auto'),
                    "width" => Mage::Helper('catalog')->__('Width'),
                    "height" => Mage::Helper('catalog')->__('Height'),
                    "both" => Mage::Helper('catalog')->__('Both'),
					);

		return ($list);
	}
}