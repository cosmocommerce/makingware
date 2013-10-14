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
 * One page checkout payment
 *
 * @category   Mage
 * @category   Mage
 * @package    Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Onepage_Method_Shipping extends Mage_Checkout_Block_Onepage_Abstract
{
	protected $_rates = null;

	/**
     * Prepare children blocks
     */
    protected function _prepareLayout()
    {
        /**
         * Create child blocks for shipping methods forms
         */
         $shippingRates = $this->getShippingRates();

         if(!empty($shippingRates)){
        	foreach ($shippingRates as $code => $rates) {
        		foreach ($rates as $code => $rate) {
        			$shippingCarrier = Mage::getModel('shipping/shipping')->getCarrierByCode($rate->getCarrier());
                    if (empty($shippingCarrier) || !$shippingCarrier instanceof Mage_Shipping_Model_Carrier_Abstract) {
                    	continue;
                    }
                    $this->setChild(
                    	'shipping.method.'.$rate->getCarrier(),
                        $this->helper('shipping')->getMethodFormBlock($shippingCarrier)
                    );
        		}
       	 	}
       	 }

        return parent::_prepareLayout();
    }

    public function getShippingRates()
    {
    	if (empty($this->_rates)) {
    		$this->_rates = Mage::getBlockSingleton('checkout/onepage')->getShippingRates();
    	}
    	return $this->_rates;
    }

	public function getCarrierName($carrierCode)
    {
        if ($name = Mage::getStoreConfig('carriers/'.$carrierCode.'/title')) {
            return $name;
        }
        return $carrierCode;
    }

    public function getAddressShippingMethod()
    {
        return $this->getAddress()->getShippingMethod();
    }

    public function getShippingPrice($price, $flag)
    {
        return $this->getQuote()->getStore()->convertPrice($price, true);
    }
    
    public function isShow()
    {
    	return !$this->getQuote()->isVirtual();
    }
}