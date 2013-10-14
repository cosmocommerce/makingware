<?php

class Mage_Directory_Model_Area extends Mage_Core_Model_Abstract
{
	protected function _construct()
	{
		$this->_init('directory/area');
	}

	/**
	 * Retrieve area name
	 *
	 * If name is no declared, then default_name is used
	 *
	 * @return string
	 */
	public function getName()
	{
		$name = $this->getData('name');
		if (is_null($name)) {
			$name = $this->getData('default_name');
		}
		return $name;
	}

	public function loadByName($name, $cityId)
	{
		$this->_getResource()->loadByName($this, $name, $cityId);
		return $this;
	}
}
