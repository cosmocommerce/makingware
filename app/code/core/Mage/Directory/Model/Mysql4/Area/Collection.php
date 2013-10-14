<?php

class Mage_Directory_Model_Mysql4_Area_Collection extends Varien_Data_Collection_Db
{
	protected $_areaTable;
	protected $_areaNameTable;
	protected $_cityTable;

	public function __construct()
	{
		parent::__construct(Mage::getSingleton('core/resource')->getConnection('directory_read'));

		$this->_cityTable = Mage::getSingleton('core/resource')->getTableName('directory/country_city');
		$this->_areaTable = Mage::getSingleton('core/resource')->getTableName('directory/country_area');
		$this->_areaNameTable = Mage::getSingleton('core/resource')->getTableName('directory/country_area_name');

		$locale = Mage::app()->getLocale()->getLocaleCode();

		$this->_select->from(array('area'=>$this->_areaTable),
			array('area_id'=>'area_id', 'city_id'=>'city_id', 'default_name'=>'default_name')
		);
		$this->_select->joinLeft(array('cname'=>$this->_areaNameTable),
			"area.area_id=cname.area_id AND cname.locale='$locale'", array('name'));

		$this->setItemObjectClass(Mage::getConfig()->getModelClassName('directory/area'));
	}

	public function addCityFilter($cityId)
	{
		if (!empty($cityId)) {
			if (is_array($cityId)) {
				$this->addFieldToFilter('area.city_id', array('in'=>$cityId));
			} else {
				$this->addFieldToFilter('area.city_id', $cityId);
			}
		}
		return $this;
	}

	public function addAreaNameFilter($areaName)
	{
		if (!empty($areaName)) {
			if (is_array($areaName)) {
				$this->_select->where("area.default_name in ('".implode("','", $areaName)."')");
			} else {
				$this->_select->where("area.default_name = '{$areaName}'");
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
