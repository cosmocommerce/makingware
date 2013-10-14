<?php

class Makingware_ViewProductAlert_Block_Products_List extends Mage_Catalog_Block_Product_Abstract
{
    protected $_collection;
    protected $_stockcollection;
    
    protected function _getHelper ()
    {
        return Mage::helper('viewproductalert');
    }
    
    public function hasProductPriceAlertItems ()
    {
        return $this->getProductPriceAlertItemsCount() > 0;
    }
    
    public function hasProductStockAlertItems ()
    {
        return $this->getProductStockAlertItemsCount() > 0;
    }
    
    public function getProductPriceAlertItemsCount ()
    {
        return $this->getProductPriceAlertItems()->count();
    }
    
    public function getProductStockAlertItemsCount ()
    {
        return $this->getProductStockAlertItems()->count();
    }
    
    public function getProductPriceAlertItems ()
    {
        if (is_null($this->_collection)) {
            $this->_collection = Mage::getResourceModel('productalert/price_collection');
            $this->_collection->addCustomerFilter(Mage::getSingleton('customer/session')->getId());
        }
        
        return $this->_collection;
    }
    
    public function getProductStockAlertItems ()
    {
        if (is_null($this->_stockcollection)) {
            $this->_stockcollection = Mage::getResourceModel('productalert/stock_collection');
            $this->_stockcollection->addCustomerFilter(Mage::getSingleton('customer/session')->getId());
        }
        
        return $this->_stockcollection;
    }
    
    public function getProductItems ($product_id)
    {
        $product = Mage::getModel('catalog/product')->load($product_id);
        
        return $product;
    }
    
    public function getFormatedDate ($date)
    {
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
    }
    
    public function getItemAddToCartUrl ($product)
    {
        return $this->_getHelper()->getAddToCartUrl($product);
    }
    
    public function getAddToCartUrl ($product, $additional = array())
    {
        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }
    
    public function getProductPriceUnsubscribeUrl ($productId)
    {
        $params['product'] = $productId;
        
        return $this->getUrl('productalert/unsubscribe/price', $params);
    }
    
    public function getProductStockUnsubscribeUrl ($productId)
    {
        $params['product'] = $productId;
        
        return $this->getUrl('productalert/unsubscribe/stock', $params);
    }
    
    public function getBackUrl ()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        
        return $this->getUrl('customer/account/');
    }
}
