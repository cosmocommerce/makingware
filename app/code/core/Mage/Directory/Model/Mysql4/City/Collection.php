<?php

class Mage_Directory_Model_Mysql4_City_Collection extends Varien_Data_Collection_Db
{
	protected $_cityTable;
	protected $_cityNameTable;
	protected $_regionTable;

	public function __construct()
	{
		parent::__construct(Mage::getSingleton('core/resource')->getConnection('directory_read'));

		$this->_regionTable = Mage::getSingleton('core/resource')->getTableName('directory/country_region');
		$this->_cityTable = Mage::getSingleton('core/resource')->getTableName('directory/country_city');
		$this->_cityNameTable = Mage::getSingleton('core/resource')->getTableName('directory/country_city_name');

		$locale = Mage::app()->getLocale()->getLocaleCode();

		$this->_select->from(array('city'=>$this->_cityTable),
			array('city_id'=>'city_id', 'region_id'=>'region_id', 'default_name'=>'default_name')
		);
		$this->_select->joinLeft(array('cname'=>$this->_cityNameTable),
			"city.city_id=cname.city_id AND cname.locale='$locale'", array('name'));

		$this->setItemObjectClass(Mage::getConfig()->getModelClassName('directory/city'));
	}

	public function addRegionFilter($regionId)
	{
		if (!empty($regionId)) {
			if (is_array($regionId)) {
				$this->addFieldToFilter('city.region_id', array('in'=>$regionId));
			} else {
				$this->addFieldToFilter('city.region_id', $regionId);
			}
		}
		return $this;
	}

	public function addCityNameFilter($cityName)
	{
		if (!empty($cityName)) {
			if (is_array($cityName)) {
				$this->_select->where("city.default_name in ('".implode("','", $cityName)."')");
			} else {
				$this->_select->where("city.default_name = '{$cityName}'");
			}
		}
		return $this;
	}

	public function toOptionArray()
	{
		$options = array();
		foreach ($this as $item) {
			$options[] = array(
			   'value' => $item->getId(),
			   'label' => $item->getName()
			);
		}
		if (count($options)>0) {
			array_unshift($options, array('title'=>null, 'value'=>'0', 'label'=>Mage::helper('directory')->__('-- Please select --')));
		}
		return $options;
	}
}
