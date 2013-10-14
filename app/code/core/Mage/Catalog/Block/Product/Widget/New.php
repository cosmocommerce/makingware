<?php
class Mage_Catalog_Block_Product_Widget_New extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
	protected $_productsCount = null;

	const DEFAULT_PRODUCTS_COUNT = 5;

	protected function _construct()
	{
		parent::_construct();
		$this->addData(array(
			'cache_lifetime'    => 86400,
			'cache_tags'        => array(Mage_Catalog_Model_Product::CACHE_TAG),
		));
	}

	public function getCacheKeyInfo()
	{
		return array(
		   'CATALOGPRODUCTNEW',
		   Mage::app()->getStore()->getId(),
		   Mage::getDesign()->getPackageName(),
		   Mage::getDesign()->getTheme('template'),
		   Mage::getSingleton('customer/session')->getCustomerGroupId(),
		   'template' => $this->getTemplate(),
		   $this->getCategoryId(),
		   $this->getProductsCount()
		);
	}

	protected function _beforeToHtml()
	{
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $collection = Mage::getResourceModel('catalog/product_collection');
		if($categoryId = $this->getCategoryId()) {
			$collection->addCategoryFilter(Mage::getModel('catalog/category')->load($categoryId));
		}
		
		$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
		$collection = $this->_addProductAttributesAndPrices($collection)
			->addStoreFilter()
			->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
			->addAttributeToFilter('news_to_date', array('or'=> array(
				0 => array('date' => true, 'from' => $todayDate),
				1 => array('is' => new Zend_Db_Expr('null')))
			), 'left')
			->addAttributeToSort('news_from_date', 'desc')
			->setPageSize($this->getProductsCount())
			->setCurPage(1);
            
        if ($catId=$this->getCategoryId()) { 
            $collection->addUrlRewrite($catId);      
        }

		$this->setProductCollection($collection);

		return parent::_beforeToHtml();
	}

	public function setProductsCount($count)
	{
		$this->_productsCount = $count;
		return $this;
	}

	public function getProductsCount()
	{
		if (!$this->hasData('products_count')) {
			if (null === $this->_productsCount) {
				$this->_productsCount = self::DEFAULT_PRODUCTS_COUNT;
				return $this->_productsCount;
		    }
        }
		return $this->_getData('products_count');
	}
	
	public function getCategoryId()
	{
		if ($this->hasData('category_id')) {
			$ids = $this->_getData('category_id');
			if (false !== strpos($ids, '/')) {
				$ids = explode('/',$ids);
				$category_id = end($ids);
				if (is_numeric($category_id)) {
					return (int)$category_id;
				}
			}
		}
		return 0;
	}
}
