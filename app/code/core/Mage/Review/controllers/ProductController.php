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
 * Review controller
 *
 * @category   Mage
 * @package    Mage_Review
 * @author     Magento Core Team <core@magentocommerce.com>
 */
require_once('Securimage/securimage.php');//import secure code class 

class Mage_Review_ProductController extends Mage_Core_Controller_Front_Action
{

    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array('post');
    
    protected function _initNotLoggedIn()
    {
    	$this->setFlag('', self::FLAG_NO_DISPATCH, true);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_current' => true)));
        Mage::getSingleton('review/session')->setFormData($this->getRequest()->getPost())
        	->setRedirectUrl($this->_getRefererUrl());
        
        return $this;
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }
        
        $allowGuest = Mage::helper('review')->getIsGuestAllowToWrite();
        if (!$allowGuest && $this->getRequest()->getActionName() == 'post' && $this->getRequest()->isPost()) {
            if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->_initNotLoggedIn()->_redirectUrl(Mage::helper('customer')->getLoginUrl());
            }
        }

        return $this;
    }
    /**
     * Initialize and check product
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _initProduct()
    {
        Mage::dispatchEvent('review_controller_product_init_before', array('controller_action'=>$this));
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $this->getRequest()->getParam('id');

        $product = $this->_loadProduct($productId);

        if ($categoryId) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            Mage::register('current_category', $category);
        }

        try {
            Mage::dispatchEvent('review_controller_product_init', array('product'=>$product));
            Mage::dispatchEvent('review_controller_product_init_after', array('product'=>$product, 'controller_action' => $this));
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return false;
        }

        return $product;
    }

    /**
     * Load product model with data by passed id.
     * Return false if product was not loaded or has incorrect status.
     *
     * @param int $productId
     * @return bool|Mage_Catalog_Model_Product
     */
    protected function _loadProduct($productId)
    {
        if (!$productId) {
            return false;
        }

        $product = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($productId);
        /* @var $product Mage_Catalog_Model_Product */
        if (!$product->getId() || !$product->isVisibleInCatalog() || !$product->isVisibleInSiteVisibility()) {
            return false;
        }

        Mage::register('current_product', $product);
        Mage::register('product', $product);

        return $product;
    }

    /**
     * Load review model with data by passed id.
     * Return false if review was not loaded or review is not approved.
     *
     * @param int $productId
     * @return bool|Mage_Review_Model_Review
     */
    protected function _loadReview($reviewId)
    {
        if (!$reviewId) {
            return false;
        }

        $review = Mage::getModel('review/review')->load($reviewId);
        /* @var $review Mage_Review_Model_Review */
        if (!$review->getId() || !$review->isApproved() || !$review->isAvailableOnStore(Mage::app()->getStore())) {
            return false;
        }

        Mage::register('current_review', $review);

        return $review;
    }

    /**
     * Submit new review action
     *
     */
    public function postAction()
    {
        if ($data = Mage::getSingleton('review/session')->getFormData(true)) {
            $rating = array();
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data   = $this->getRequest()->getPost();
            $rating = $this->getRequest()->getParam('ratings', array());
        }

        if (($product = $this->_initProduct()) && !empty($data)) {
            $session    = Mage::getSingleton('core/session');
            //if enable secure code
            if(Mage::helper('review')->canShowSecureCode()) {
            	$verifyCode = Mage::getSingleton('review/session')->getReviewVerifyCode();
            	if (empty($verifyCode) || strtolower($verifyCode) != strtolower($data['secure_code'])) {
            		$session->setFormData($data);
            		$session->addError($this->__('Secure Code Error!'));
            		return $this->_redirectReferer();
            	}
            }
            /* @var $session Mage_Core_Model_Session */
            $review     = Mage::getModel('review/review')->setData($data);
            /* @var $review Mage_Review_Model_Review */

            $validate = $review->validate();
            if ($validate === true) {
                try {
                	
	                if (!Mage::helper('review')->getIsBuyAllowToWrite()) {
	        			if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
	        				$this->_initNotLoggedIn()->_redirectUrl(Mage::helper('customer')->getLoginUrl());
	        			}
	        		
	        			if (false == Mage::helper('review')->checkProductInOrder(Mage::getSingleton('customer/session')->getCustomer(), $product)) {
	        				Mage::throwException($this->__('Only this product allows users to buy post the review.'));
	        			}
	        		}
                	
                    if(Mage::helper('review')->isNeedVerify()){
                        $status=Mage_Review_Model_Review::STATUS_PENDING;
                    }else{
                        $status=Mage_Review_Model_Review::STATUS_APPROVED;
                    }
                    
                    $review->setEntityId($review->getEntityIdByCode(Mage_Review_Model_Review::ENTITY_PRODUCT_CODE))
                        ->setEntityPkValue($product->getId())
                        ->setStatusId($status)
                        ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->setStores(array(Mage::app()->getStore()->getId()))
                        ->save();

                    foreach ($rating as $ratingId => $optionId) {
                        Mage::getModel('rating/rating')
                        ->setRatingId($ratingId)
                        ->setReviewId($review->getId())
                        ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                        ->addOptionVote($optionId, $product->getId());
                    }

                    $review->aggregate();
                    $session->addSuccess($this->__('Your review has been accepted for moderation.'));
                }
                catch (Mage_Core_Exception $e) {
                	$session->setFormData($data);
                    $session->addError($e->getMessage());
                }
                catch (Exception $e) {
                    $session->setFormData($data);
                    $session->addError($this->__('Unable to post the review.'));
                }
            }
            else {
                $session->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $session->addError($errorMessage);
                    }
                }
                else {
                    $session->addError($this->__('Unable to post the review.'));
                }
            }
        }

        if ($redirectUrl = Mage::getSingleton('review/session')->getRedirectUrl(true)) {
            $this->_redirectUrl($redirectUrl);
            return;
        }
        $this->_redirectReferer();
    }

    /**
     * Show list of product's reviews
     *
     */
    public function listAction()
    {
        if ($product = $this->_initProduct()) {
            Mage::register('productId', $product->getId());

            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($product);
            if ($settings->getCustomDesign()) {
                $design->applyCustomDesign($settings->getCustomDesign());
            }
            $this->_initProductLayout($product);

            // update breadcrumbs
            if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbsBlock->addCrumb('product', array(
                    'label'    => $product->getName(),
                    'link'     => $product->getProductUrl(),
                    'readonly' => true,
                ));
                $breadcrumbsBlock->addCrumb('reviews', array('label' => Mage::helper('review')->__('Product Reviews')));
            }

            $this->renderLayout();
        } elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }

    /**
     * Show details of one review
     *
     */
    public function viewAction()
    {
        $review = $this->_loadReview((int) $this->getRequest()->getParam('id'));
        if (!$review) {
            $this->_forward('noroute');
            return;
        }

        $product = $this->_loadProduct($review->getEntityPkValue());
        if (!$product) {
            $this->_forward('noroute');
            return;
        }

        $this->loadLayout();
        $this->_initLayoutMessages('review/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    /**
     * Load specific layout handles by product type id
     *
     */
    protected function _initProductLayout($product)
    {
        $update = $this->getLayout()->getUpdate();

        $update->addHandle('default');
        $this->addActionLayoutHandles();


        $update->addHandle('PRODUCT_TYPE_'.$product->getTypeId());

        if ($product->getPageLayout()) {
            $this->getLayout()->helper('page/layout')
                ->applyHandle($product->getPageLayout());
        }

        $this->loadLayoutUpdates();
        if ($product->getPageLayout()) {
            $this->getLayout()->helper('page/layout')
                ->applyTemplate($product->getPageLayout());
        }
        $update->addUpdate($product->getCustomLayoutUpdate());
        $this->generateLayoutXml()->generateLayoutBlocks();
    }
    
     public function generalImageAction()
     {
     	$source = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
     	# Randomly generated a four digit verification code
     	$str = '';
     	for ($i=0; $i<4; $i++) {
     		$str .= $source[rand(0, strlen($source) - 1)];
     	}
     	
     	Mage::getSingleton('review/session')->setReviewVerifyCode($str);
     	
     	# To create pictures, define color value
     	$img = imagecreate(60, 25);
     	$black = imagecolorallocate($img, 0, 0, 0);
     	$gray = imagecolorallocate($img, 200, 200, 200);
     	imagefill($img, 0, 0, $gray);
     	
     	# In the canvas randomly generated a large black spots, the interference
     	for ($i=0; $i<80; $i++) {
     		imagesetpixel($img, rand(0, 60), rand(0, 20), $black);
     	}
     	
     	$strx = rand(3, 8);
     	for($i=0; $i<4; $i++) {
     		imagestring($img, 5, $strx, rand(1, 6), substr($str, $i, 1), $black);
     		$strx += rand(8, 12);
     	}
     	
     	header('Pragma', 'no-cache');
     	header('Content-Type', 'image/jpeg');
     	
     	imagejpeg($img);
     	imagedestroy($img);
     	
     	exit;
     }
}