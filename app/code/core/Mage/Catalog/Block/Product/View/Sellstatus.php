<?php
 class Mage_Catalog_Block_Product_View_Sellstatus extends Mage_Core_Block_Template
 {
    protected function _construct()
    {
        $this->setTemplate('catalog/product/view/sellstatus.phtml');
    }
	
	/**
     * If is Sellstatus product
     *
     * @return Mage_Catalog_Block_Product_Abstract
     */

    public function isSellstatusProduct()
    {   
    	$product = $this->getProduct();
     	$sellstatusId = $product->getData('sellstatus');
		$attributes = $product->getAttributes();
		$sellstatus = $attributes['sellstatus'];
		if (empty($sellstatusId)) {
			return '';
		}
		return $sellstatus->getFrontend()->getValue($product);
    }

     /**
     * If is new product
     *
     * @return Mage_Catalog_Block_Product_Abstract
     */

    public function isNewProduct()
    {
     	$product = $this->getProduct();
    	if (!is_empty_date($product->getNewsFromDate()) && Mage::app()->getLocale()->isStoreDateInInterval(null, $product->getNewsFromDate(), $product->getNewsToDate())) {
    		return true;
    	}
    	return false;
    }

    /**
     * If is special product
     *
     * @return Mage_Catalog_Block_Product_Abstract
     */
    public function isSpecialProduct()
    {
    	$product = $this->getProduct();
    	if ($product->getSpecialPrice() && Mage::app()->getLocale()->isStoreDateInInterval(null, $product->getSpecialFromDate(), $product->getSpecialToDate())) {
    		return true;
    	}
    	return false;
    }

     /**
     * If is bestseller product
     *
     * @return Mage_Catalog_Block_Product_Abstract
     */
    public function isBestsellerProduct()
    {
    	$product = $this->getProduct();
    	if (!is_empty_date($product->getBestsellersFromDate()) && Mage::app()->getLocale()->isStoreDateInInterval(null, $product->getBestsellersFromDate(), $product->getBestsellersToDate())) {
    		return true;
    	}
    	return false;
    }
 }
