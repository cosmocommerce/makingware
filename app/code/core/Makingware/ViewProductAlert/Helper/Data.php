<?php

class Makingware_ViewProductAlert_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected function _getUrlStore ($item)
    {
        $storeId = null;
        if ($item instanceof Mage_Catalog_Model_Product) {
            if ($item->isVisibleInSiteVisibility()) {
                $storeId = $item->getStoreId();
            } else 
                if ($item->hasUrlDataObject()) {
                    $storeId = $item->getUrlDataObject()->getStoreId();
                }
        }
        return Mage::app()->getStore($storeId);
    }
    
    public function getAddToCartUrl ($item)
    {
        $urlParamName = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;
        
        $continueUrl = Mage::helper('core')->urlEncode(
            Mage::getUrl('*/*/*', 
            array('_current' => true, '_use_rewrite' => true, 
        	'_store_to_url' => true))
        );
        
        return $this->_getUrlStore($item)->getUrl('wishlist/index/cart', 
            array('item' => $item->getWishlistItemId(), 
            $urlParamName => $continueUrl)
        );
    }
}
