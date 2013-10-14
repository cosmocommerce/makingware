<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Directory
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Directory data helper
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Directory_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_countryCollection;
    protected $_regionCollection;
    protected $_cityCollection;
    protected $_areaCollection;
    protected $_regionJson;
    protected $_cityJson;
    protected $_areaJson;
    protected $_currencyCache = array();
    protected $_optionalZipCountries = null;

    public function getAreaCollection()
    {
        if (!$this->_areaCollection) {
            $this->_areaCollection = Mage::getModel('directory/area')->getResourceCollection();
            if (method_exists($this, 'getAddress')) {
                $this->_areaCollection = $this->_areaCollection->addRegionFilter($this->getAddress()->getCityId());
            }
            $this->_areaCollection = $this->_areaCollection->load();
        }
        return $this->_areaCollection;
    }

    public function getCityCollection()
    {
        if (!$this->_cityCollection) {
            $this->_cityCollection = Mage::getModel('directory/city')->getResourceCollection();
            if (method_exists($this, 'getAddress')) {
                $this->_cityCollection = $this->_cityCollection->addRegionFilter($this->getAddress()->getRegionId());
            }
            $this->_cityCollection = $this->_cityCollection->load();
        }
        return $this->_cityCollection;
    }

    public function getRegionCollection()
    {
        if (!$this->_regionCollection) {
            $this->_regionCollection = Mage::getModel('directory/region')->getResourceCollection();
            if (method_exists($this, 'getAddress')) {
                $this->_regionCollection->addCountryFilter($this->getAddress()->getCountryId());
            } else {
                $this->_regionCollection->addCountryFilter('CN');
            }
            $this->_regionCollection = $this->_regionCollection->load();
        }
        return $this->_regionCollection;
    }

    public function getCountryCollection()
    {
        if (!$this->_countryCollection) {
            $this->_countryCollection = Mage::getModel('directory/country')->getResourceCollection()
                ->loadByStore();
        }
        return $this->_countryCollection;
    }

    /**
     * Retrieve regions data json
     *
     * @return string
     */
    public function getRegionJson()
    {

        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_regionJson) {
            $cacheKey = 'DIRECTORY_REGIONS_JSON_STORE'.Mage::app()->getStore()->getId();
            if (Mage::app()->useCache('config')) {
                $json = Mage::app()->loadCache($cacheKey);
            }
            if (empty($json)) {
                $countryIds = array();
                foreach ($this->getCountryCollection() as $country) {
                    $countryIds[] = $country->getCountryId();
                }
                $collection = Mage::getModel('directory/region')->getResourceCollection()
                    ->addCountryFilter($countryIds)
                    ->load();
                $regions = array();
                foreach ($collection as $region) {
                    if (!$region->getRegionId()) {
                        continue;
                    }
                    $regions[$region->getCountryId()][$region->getRegionId()] = array(
                        'code'=>$region->getCode(),
                        'name'=>$region->getName()
                    );
                }
                $json = Mage::helper('core')->jsonEncode($regions);

                if (Mage::app()->useCache('config')) {
                    Mage::app()->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_regionJson = $json;
        }

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $this->_regionJson;
    }

    /**
     * Retrieve cities data json
     *
     * @return string
     */
    public function getCityJson()
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_cityJson) {
            $cacheKey = 'DIRECTORY_CITIES_JSON_STORE'.Mage::app()->getStore()->getId();
            if (Mage::app()->useCache('config')) {
                $json = Mage::app()->loadCache($cacheKey);
            }
            if (empty($json)) {
                $regionIds = array();
                foreach ($this->getRegionCollection() as $region) {
                    $regionIds[] = $region->getRegionId();
                }
                $collection = Mage::getModel('directory/city')->getResourceCollection()
                    ->addRegionFilter($regionIds)
                    ->load();
                $cities = array();
                foreach ($collection as $city) {
                    if (!$city->getCityId()) {
                        continue;
                    }
                    $cities[$city->getRegionId()][$city->getCityId()] = array(
                        'name'=>$city->getName()
                    );
                }
                $json = Mage::helper('core')->jsonEncode($cities);

                if (Mage::app()->useCache('config')) {
                    Mage::app()->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_cityJson = $json;
        }

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $this->_cityJson;
    }
    
    /**
     * Retrieve cities data json
     *
     * @return string
     */
    public function getAreaJson()
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_areaJson) {
            $cacheKey = 'DIRECTORY_AREAS_JSON_STORE'.Mage::app()->getStore()->getId();
            if (Mage::app()->useCache('config')) {
                $json = Mage::app()->loadCache($cacheKey);
            }
            if (empty($json)) {
                $cityIds = array();
                foreach ($this->getCityCollection() as $city) {
                    $cityIds[] = $city->getCityId();
                }
                $collection = Mage::getModel('directory/area')->getResourceCollection()
                    ->addCityFilter($cityIds)
                    ->load();
                $areas = array();
                foreach ($collection as $area) {
                    if (!$area->getAreaId()) {
                        continue;
                    }
                    $areas[$area->getCityId()][$area->getAreaId()] = array(
                        'name'=>$area->getName()
                    );
                }
                $json = Mage::helper('core')->jsonEncode($areas);

                if (Mage::app()->useCache('config')) {
                    Mage::app()->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_areaJson = $json;
        }

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $this->_areaJson;
    }

    public function currencyConvert($amount, $from, $to=null)
    {
        if (empty($this->_currencyCache[$from])) {
            $this->_currencyCache[$from] = Mage::getModel('directory/currency')->load($from);
        }
        if (is_null($to)) {
            $to = Mage::app()->getStore()->getCurrentCurrencyCode();
        }
        $converted = $this->_currencyCache[$from]->convert($amount, $to);
        return $converted;
    }

    /**
     * Return ISO2 country codes, which have optional Zip/Postal pre-configured
     *
     * @param bool $asJson
     * @return array
     */
    public function getCountriesWithOptionalZip($asJson = false)
    {
        if (null === $this->_optionalZipCountries) {
            $this->_optionalZipCountries = preg_split('/\,/', Mage::getStoreConfig('general/country/optional_zip_countries'),
                0, PREG_SPLIT_NO_EMPTY
            );
        }
        if ($asJson) {
            return Mage::helper('core')->jsonEncode($this->_optionalZipCountries);
        }
        return $this->_optionalZipCountries;
    }

    /**
     * Check whether zip code is optional for specified country code
     * @param string $countryCode
     */
    public function isZipCodeOptional($countryCode)
    {
        $this->getCountriesWithOptionalZip();
        return in_array($countryCode, $this->_optionalZipCountries);
    }
    
	/**
	 * default country
	 * @return string
	 */
	public function getDefaultCountry()
	{
		return Mage::getStoreConfig('general/country/default');
	}
	
	/**
	 * more country
	 * @return string
	 */
	public function getMoreCountry()
	{
		return Mage::getStoreConfig('general/country/more');
	}
	
	/**
	 * allow country
	 * @return string
	 */
	public function getAllowCountry()
	{
		return Mage::getStoreConfig('general/country/allow');
	}
	
	/**
	 * optional zip countries country
	 * @return string
	 */
	public function getOptionalZipCountriesCountry()
	{
		return Mage::getStoreConfig('general/country/optional_zip_countries');
	}
}