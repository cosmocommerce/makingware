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
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Onepage checkout block
 *
 * @category   Mage
 * @package    Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Onepage extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getSteps()
    {
        $steps = array();

        if (!$this->isCustomerLoggedIn()) {
            $steps['login'] = $this->getCheckout()->getStepData('login');
        }

        $stepCodes = array('shipping', 'shipping_method', 'payment', 'review');

        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
        return $steps;
    }

    public function getActiveStep()
    {
        return 'login';
    }
    
    public function getShippingRates()
    {
        if (empty($this->_rates)) {
        	$quoteAddress = $this->getAddress();
        	if (count($quoteAddress->getShippingRatesCollection()) == 0) {
        		$quoteAddress->setCollectShippingRates(true)
        			->collectShippingRates()
        			->save();
        	}
            $this->_rates = $this->getAddress()->getGroupedAllShippingRates();
        }
        return $this->_rates;
    }
    
	/**
     * Check and prepare payment method model
     *
     * Redeclare this method in child classes for declaring method info instance
     *
     * @return bool
     */
    protected function _assignMethod($method)
    {
        $method->setInfoInstance($this->getQuote()->getPayment());
        return $this;
    }
    
	protected function _canUseMethod($method)
    {
        if (! $method->canUseForCurrency(Mage::app()->getStore()->getBaseCurrencyCode())) {
            return false;
        }
        
        /**
         * Checking for min/max order total for assigned payment method
         */
        $total = $this->getQuote()->getBaseGrandTotal();
        $minTotal = $method->getConfigData('min_order_total');
        $maxTotal = $method->getConfigData('max_order_total');
        
        if ((! empty($minTotal) && ($total < $minTotal)) || (! empty($maxTotal) && ($total > $maxTotal))) {
            return false;
        }
        return true;
    }
    
	/**
     * Retrieve availale payment methods
     *
     * @return array
     */
    public function getMethods()
    {
        $methods = $this->getData('methods');
        if (is_null($methods)) {
        	Mage::dispatchEvent('checkout_onepage_block_payment_methods', array('quote' => $this->getQuote()));
        	
            $store = $this->getQuote() ? $this->getQuote()->getStoreId() : null;
            $methods = $this->helper('payment')->getStoreMethods($store, $this->getQuote());
            
            foreach ($methods as $key => $method) {
                if ($this->_canUseMethod($method)) {
                    $this->_assignMethod($method);
                } else {
                    unset($methods[$key]);
                }
            }
            
            $this->setData('methods', $methods);
        }
        return $methods;
    }
    
	public function getCarrierName($carrierCode)
    {
        if ($name = Mage::getStoreConfig('carriers/' . $carrierCode . '/title')) {
            return $name;
        }
        return $carrierCode;
    }
    
    public function getCouponCode()
    {
        return $this->getQuote()->getCouponCode();
    }
    
    public function isAllowedGuestCheckout()
    {
        return $this->getQuote()->isAllowedGuestCheckout();
    }
}
