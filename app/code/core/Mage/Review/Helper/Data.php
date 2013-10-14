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
 * @package     Mage_Review
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Default review helper
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Review_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_REVIEW_GUETS_ALLOW = 'catalog/review/allow_guest';
    const XML_REVIEW_BUY_ALLOW = 'catalog/review/allow_buy';
    const XML_REVIEW_SECURECODE_ALLOW = 'catalog/review/allow_securecode';
    const XML_REVIEW_VERIFY_ALLOW = 'catalog/review/allow_verify';
    
    protected $_reviewNotice = '';

    public function getDetail($origDetail)
    {
        return nl2br(Mage::helper('core/string')->truncate($origDetail, 50));
    }

    /**
     * getDetailHtml return short detail info in HTML
     * @param string $origDetail Full detail info
     * @return string
     */
    public function getDetailHtml($origDetail)
    {
        return nl2br(Mage::helper('core/string')->truncate($this->escapeHtml($origDetail), 50));
    }

    public function getIsGuestAllowToWrite()
    {
        return Mage::getStoreConfigFlag(self::XML_REVIEW_GUETS_ALLOW);
    }
    
    public function getIsBuyAllowToWrite()
    {
        return Mage::getStoreConfigFlag(self::XML_REVIEW_BUY_ALLOW);
    }
    
    public function checkProductInOrder(Mage_Customer_Model_Customer $customer, $productId)
    {
    	if ($productId instanceof Mage_Catalog_Model_Product) {
    		$productId = $productId->getId();
    	}
    	
    	if (!$customer->getId() || !$productId) {
    		return false;
    	}
    	
    	$collection = Mage::getModel('sales/order')->getCollection();
    	$select = $collection->getSelect()
    		->where('customer_id = ?', $customer->getId())
    		->where('status = ?', 'complete')
    		->columns('main_table.' . $collection->getResource()->getIdFieldName());
    	$ids = $collection->getConnection()->fetchCol($select);
    	
    	if (empty($ids)) {
    		return false;
    	}

    	return (boolean)(count(
    		Mage::getResourceModel('sales/order_item_collection')
    		->addFieldToFilter('order_id', array('in' => $ids))
    		->addFieldToFilter('product_id', $productId)
    	) > 0);
    }
    
    public function isDisplayReview(Mage_Catalog_Model_Product $product)
    {
    	$this->_reviewNotice = '';
        
    	if (!$this->getIsGuestAllowToWrite() && !Mage::getSingleton('customer/session')->isLoggedIn()) {
    		$this->_reviewNotice = $this->__('Only logined users to buy post the review.');
    		return false;
    	}
    	
    	if (!$this->getIsBuyAllowToWrite()) {
    		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
    			$this->_reviewNotice = $this->__('Only this product allows users to buy post the review.');
    			return false;
    		}
    		if (false == $this->checkProductInOrder(Mage::getSingleton('customer/session')->getCustomer(), $product)) {
    			$this->_reviewNotice = $this->__('Only this product allows users to buy post the review.');
    			return false;
    		}
    	}
    	
    	return true;
    }
    
    public function getReviewNotice()
    {
    	return $this->_reviewNotice;
    }
    
    public function canShowSecureCode()
    {
        return Mage::getStoreConfigFlag(self::XML_REVIEW_SECURECODE_ALLOW);
    }
     
    public function isNeedVerify()
    {
        return Mage::getStoreConfigFlag(self::XML_REVIEW_VERIFY_ALLOW);
    }
}
