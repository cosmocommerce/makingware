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
 * One page checkout shipping
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Onepage_Shipping extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * Initialize shipping address step
     */
    protected function _construct()
    {
        $this->getCheckout()->setStepData('shipping', array(
            'label'     => Mage::helper('checkout')->__('Shipping Information'),
            'is_show'   => $this->isShow()
        ));

        parent::_construct();
    }

    /**
     * Return checkout method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->getQuote()->getCheckoutMethod();
    }

    /**
     * Retrieve is allow and show block
     *
     * @return bool
     */
    public function isShow()
    {
        return !$this->getQuote()->isVirtual();
    }
    
	public function getCountryMore()
    {
    	return Mage::helper('directory')->getMoreCountry();
    }
    
    public function getCustomerDefaultAddress()
    {
    	if (!$this->hasData('customer_default_address')) {
    		$this->setData('customer_default_address', $this->getCustomer()->getDefaultShippingAddress());
    	}
    	return $this->getData('customer_default_address');
    }
    
	public function getCountryId()
    {
    	if ($countryId = $this->getAddress()->getCountryId()) {
    		return $countryId;
    	}
    	if (($address = $this->getCustomerDefaultAddress()) && ($countryId = $address->getCountryId())) {
    		return $countryId;
    	}
        return Mage::helper('directory')->getDefaultCountry();
    }
    
    public function __call($method, $args)
    {
    	if ($result = $this->getAddress()->__call($method, $args)) {
    		return $result;
    	}
    	if (($address = $this->getCustomerDefaultAddress()) && ($result = $address->__call($method, $args))) {
    		return $result;
    	}
    	return parent::__call($method, $args);
    }
}