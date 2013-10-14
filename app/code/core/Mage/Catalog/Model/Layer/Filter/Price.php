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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Layer price filter
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Model_Layer_Filter_Price extends Mage_Catalog_Model_Layer_Filter_Abstract
{
    const XML_PATH_RANGE_CALCULATION    = 'catalog/layered_navigation/price_range_calculation';
    const XML_PATH_RANGE_STEP           = 'catalog/layered_navigation/price_range_step';

    const RANGE_CALCULATION_AUTO    = 'auto';
    const RANGE_CALCULATION_MANUAL  = 'manual';
    const MIN_RANGE_POWER = 10;

    /**
     * Resource instance
     *
     * @var Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
     */
    protected $_resource;

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_requestVar = 'price';
    }

    /**
     * Retrieve resource instance
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getResourceModel('catalog/layer_filter_price');
        }
        return $this->_resource;
    }

    /**
     * Get price range for building filter steps
     *
     * @return int
     */
    public function getPriceRange()
    {
        $range = $this->getData('price_range');
        if (!$range) {
            $currentCategory = Mage::registry('current_category_filter');
            if ($currentCategory) {
                $range = $currentCategory->getFilterPriceRange();
            } else {
                $range = $this->getLayer()->getCurrentCategory()->getFilterPriceRange();
            }

            $maxPrice = $this->getMaxPriceInt();
            if (!$range) {
                $calculation = Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_CALCULATION);
                if ($calculation == self::RANGE_CALCULATION_AUTO) {
                    $index = 1;
                    do {
                        $range = pow(10, (strlen(floor($maxPrice)) - $index));
                        $items = $this->getRangeItemCounts($range);
                        $index++;
                    }
                    while($range > self::MIN_RANGE_POWER && count($items) < 2);
                } else {
                    $range = Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_STEP);
                }
            }

            while (ceil($maxPrice / $range) > 25) {
                $range *= 10;
            }

            $this->setData('price_range', $range);
        }

        return $range;
    }
    
    /**
     * Get price range for building filter steps
     *
     * @return int|array
     */
    public function getPriceRanges()
    {
    	$range = $this->getData('price_range');
    	if (!$range) {
    		$currentCategory = Mage::registry('current_category_filter');
    		if ($currentCategory) {
    			$range = $currentCategory->getFilterPriceRange();
    		} else {
    			$range = $this->getLayer()->getCurrentCategory()->getFilterPriceRange();
    		}
    		
    		if (empty($range)) {
    			$calculation = Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_CALCULATION);
    			if ($calculation != self::RANGE_CALCULATION_AUTO) {
    				$range = Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_STEP);
    			}
    		}
    		
    		$range = trim($range);
    		if ($range) {
	    		if (false !== strpos($range, ',')) {
	    			$result = array();
	    			foreach (array_unique(explode(',', $range)) as $value) {
	    				$value = trim($value);
	    				if (is_numeric($value) && $value > 0) {
	    					$result[] = $value;
	    				}
	    			}
	    			$range = $result;
	    			sort($range);
	    		}else {
	    			$range = (int)$range;
	    		}
    		}
    		
    		if (empty($range)) {
    			$maxPrice = $this->getMaxPriceInt();
    			$index = 1;
    			do {
    				$range = pow(10, (strlen(floor($maxPrice)) - $index));
    				$items = $this->getRangeItemCounts($range);
    				$index++;
    			}
    			while($range > self::MIN_RANGE_POWER && count($items) < 2);
    			
    			while (ceil($maxPrice / $range) > 25) {
    				$range *= 10;
    			}
    		}
    		$this->setData('price_range', $range);
    	}
    
    	return $range;
    }

    /**
     * Get maximum price from layer products set
     *
     * @return float
     */
    public function getMaxPriceInt()
    {
        $maxPrice = $this->getData('max_price_int');
        if (is_null($maxPrice)) {
            $maxPrice = $this->_getResource()->getMaxPrice($this);
            $maxPrice = floor($maxPrice);
            $this->setData('max_price_int', $maxPrice);
        }

        return $maxPrice;
    }

    /**
     * Get information about products count in range
     *
     * @param   int $range
     * @return  int
     */
    public function getRangeItemCounts($range)
    {
        $rangeKey = 'range_item_counts_' . $range;
        $items = $this->getData($rangeKey);
        if (is_null($items)) {
            $items = $this->_getResource()->getCount($this, $range);
            $this->setData($rangeKey, $items);
        }

        return $items;
    }

    /**
     * Prepare text of item label
     *
     * @param   int $range
     * @param   float $value
     * @return  string
     */
    /*protected function _renderItemLabel($range, $value)
    {
        $store      = Mage::app()->getStore();
        $fromPrice  = $store->formatPrice(($value-1)*$range);
        $toPrice    = $store->formatPrice($value*$range);

        return Mage::helper('catalog')->__('%s - %s', $fromPrice, $toPrice);
    }*/
    
    protected function _renderItemLabel($minPrice, $maxPrice)
    {
    	# Mage::app()->getStore()->formatPrice($minPrice);
    	if (empty($maxPrice)) {
    		return $minPrice . ' 以上';
    	}
    	return Mage::helper('catalog')->__('%s - %s', $minPrice, $maxPrice);
    }

    /**
     * Get price aggreagation data cache key
     * @deprecated after 1.4
     * @return string
     */
    protected function _getCacheKey()
    {
        return $this->getLayer()->getStateKey()
            . '_PRICES_GRP_' . Mage::getSingleton('customer/session')->getCustomerGroupId()
            . '_CURR_' . Mage::app()->getStore()->getCurrentCurrencyCode()
            . '_ATTR_' . $this->getAttributeModel()->getAttributeCode()
            . '_LOC_'
            ;
    }
    
    /**
     * Get data for build price filter items
     *
     * @return array
     */
	/*protected function _getItemsData()
    {
        $range      = $this->getPriceRange();
        $dbRanges   = $this->getRangeItemCounts($range);
        $data       = array();

        foreach ($dbRanges as $index=>$count) {
            $data[] = array(
                'label' => $this->_renderItemLabel($range, $index),
                'value' => $index . ',' . $range,
                'count' => $count,
            );
        }

        return $data;
    }*/
    
    protected function _getItemsData()
    {
    	$data = array();
    	$dbRanges = $this->_getResource()->getRangePrices($this, $this->getPriceRanges(), false);
    	$lastItem = false;
    	if (isset($dbRanges[0]) && $dbRanges[0] > 0) {
    		$lastItem = array(
    			'min'	=> end(array_keys($dbRanges)),
    			'max'	=> 0,
    			'count'	=> $dbRanges[0]
    		);
    		unset($dbRanges[0]);
    	}
    	
    	ksort($dbRanges);
    	foreach ($dbRanges as $range => $count) {
    		if ($count > 0) {
    			$dataLength = count($data);
	    		$minPrice = $dataLength > 0 ? $data[$dataLength-1]['range'] : 0;
	    		$maxPrice = $range - 1;
	    		
	    		$data[] = array(
	    			'label' => $this->_renderItemLabel($minPrice, $maxPrice),
	    			'value' => $minPrice . ',' . $maxPrice,
	    			'count' => $count,
	    			'range'	=> $range
	    		);
    		}
    	}
    	if ($lastItem) {
    		$data[] = array(
    			'label'	=> $this->_renderItemLabel($lastItem['min'], $lastItem['max']),
    			'value'	=> $lastItem['min'] . ',' . $lastItem['max'],
    			'count' => $lastItem['count'],
    		);
    	}
    	return $data;
    }
    
    /**
     * Apply price range filter to collection
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param $filterBlock
     *
     * @return Mage_Catalog_Model_Layer_Filter_Price
     */
    /*public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = $request->getParam($this->getRequestVar());
        if (!$filter) {
            return $this;
        }

        $filter = explode(',', $filter);
        if (count($filter) != 2) {
            return $this;
        }

        list($index, $range) = $filter;

        if ((int)$index && (int)$range) {
            $this->setPriceRange((int)$range);

            $this->_applyToCollection($range, $index);
            $this->getLayer()->getState()->addFilter(
                $this->_createItem($this->_renderItemLabel($range, $index), $filter)
            );

            $this->_items = array();
        }
        return $this;
    }*/
    
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
    	/**
    	 * Filter must be string: $index,$range
    	 */
    	$filter = $request->getParam($this->getRequestVar());
    	if (empty($filter)) {
    		return $this;
    	}
    	
    	$filter = explode(',', $filter);
        if (count($filter) != 2) {
            return $this;
        }
        
        list($minPrice, $maxPrice) = $filter;
        if (is_numeric($minPrice) && is_numeric($maxPrice)) {
        	$this->_applyToCollection($minPrice, $maxPrice);
        	 
        	$this->getLayer()->getState()->addFilter(
        		$this->_createItem($this->_renderItemLabel($minPrice, $maxPrice), $filter)
        	);
        	 
        	$this->_items = array();
        }
    	return $this;
    }

    /**
     * Apply filter value to product collection based on filter range and selected value
     *
     * @param int $range
     * @param int $index
     * @return Mage_Catalog_Model_Layer_Filter_Price
     */
    protected function _applyToCollection($range, $index)
    {
        $this->_getResource()->applyFilterToCollection($this, $range, $index);
        return $this;
    }

    /**
     * Retrieve active customer group id
     *
     * @return int
     */
    public function getCustomerGroupId()
    {
        $customerGroupId = $this->_getData('customer_group_id');
        if (is_null($customerGroupId)) {
            $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        }
        return $customerGroupId;
    }

    /**
     * Set active customer group id for filter
     *
     * @param int $customerGroupId
     * @return Mage_Catalog_Model_Layer_Filter_Price
     */
    public function setCustomerGroupId($customerGroupId)
    {
        return $this->setData('customer_group_id', $customerGroupId);
    }

    /**
     * Retrieve active currency rate for filter
     *
     * @return float
     */
    public function getCurrencyRate()
    {
        $rate = $this->_getData('currency_rate');
        if (is_null($rate)) {
            $rate = Mage::app()->getStore($this->getStoreId())->getCurrentCurrencyRate();
        }
        if (!$rate) {
            $rate = 1;
        }
        return $rate;
    }

    /**
     * Set active currency rate for filter
     *
     * @param float $rate
     * @return Mage_Catalog_Model_Layer_Filter_Price
     */
    public function setCurrencyRate($rate)
    {
        return $this->setData('currency_rate', $rate);
    }
}
