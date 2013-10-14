<?php
class Makingware_Cms_Helper_Page extends Mage_Cms_Helper_Page
{
    public function renderPage(Mage_Core_Controller_Front_Action $action, $pageId = null)
    {
        $storeId = Mage::app()->getStore()->getId();
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        if (! $this->isAllowed($storeId, $customerGroupId, $pageId)) {
            return false;
        }

        return parent::renderPage($action, $pageId);
    }

    public function isAllowed($storeId, $customerGroupId, $pageId)
    {
        if (! Mage::getStoreConfigFlag('cms/makingware/permissions_enabled')) {
            return true;
        }

        return Mage::getResourceModel('cms/page_permission')->exists($storeId, $customerGroupId, $pageId);
    }
}