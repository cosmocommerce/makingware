<?php

class Mage_Directory_Model_Mysql4_Area
{
	protected $_areaTable;
	protected $_areaNameTable;

	/**
	 * DB read connection
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_read;

	/**
	 * DB write connection
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_write;

	public function __construct()
	{
		$resource = Mage::getSingleton('core/resource');
		$this->_areaTable     = $resource->getTableName('directory/country_area');
		$this->_areaNameTable = $resource->getTableName('directory/country_area_name');
		$this->_read    = $resource->getConnection('directory_read');
		$this->_write   = $resource->getConnection('directory_write');
	}

	public function getIdFieldName()
	{
		return 'area_id';
	}

	public function load(Mage_Directory_Model_Area $area, $areaId)
	{
		$locale = Mage::app()->getLocale()->getLocaleCode();
		$systemLocale = Mage::app()->getDistroLocaleCode();

		$select = $this->_read->select()
			->from(array('area'=>$this->_areaTable))
			->where('area.area_id=?', $areaId)
			->join(array('cname'=>$this->_areaNameTable),
				'cname.area_id=area.area_id AND (cname.locale=\''.$locale.'\' OR cname.locale=\''.$systemLocale.'\')',
				array('name', new Zend_Db_Expr('CASE cname.locale WHEN \''.$systemLocale.'\' THEN 1 ELSE 0 END sort_locale')))
			->order('sort_locale')
			->limit(1);

		$area->setData($this->_read->fetchRow($select));
		return $this;
	}

	public function loadByCode(Mage_Directory_Model_Area $area, $areaCode, $regionId)
	{
		$locale = Mage::app()->getLocale()->getLocaleCode();

		$select = $this->_read->select()
			->from(array('area'=>$this->_areaTable))
			->where('area.region_id=?', $regionId)
			->where('area.code=?', $areaCode)
			->join(array('cname'=>$this->_areaNameTable),
				'cname.area_id=area.area_id AND cname.locale=\''.$locale.'\'',
				array('name'));

		$area->setData($this->_read->fetchRow($select));
		return $this;
	}

	public function loadByName(Mage_Directory_Model_Area $area, $areaName, $regionId)
	{
		$locale = Mage::app()->getLocale()->getLocaleCode();

		$select = $this->_read->select()
			->from(array('area'=>$this->_areaTable))
			->where('area.region_id=?', $regionId)
			->where('area.default_name=?', $areaName)
			->join(array('cname'=>$this->_areaNameTable),
				'cname.area_id=area.area_id AND cname.locale=\''.$locale.'\'',
				array('name'));

		$area->setData($this->_read->fetchRow($select));
		return $this;
	}
}
