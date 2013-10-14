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
class Mage_Checkout_Block_Onepage_Method_Payment extends Mage_Checkout_Block_Onepage_Abstract
{
	protected $_methods = null;
	
	/**
     * Prepare children blocks
     */
    protected function _prepareLayout()
    {
        /**
         * Create child blocks for payment methods forms
         */
        foreach ($this->getMethods() as $method) {
            $this->setChild(
               'payment.method.'.$method->getCode(),
               $this->helper('payment')->getMethodFormBlock($method)
            );
        }
        return parent::_prepareLayout();
    }
	
    public function getMethods()
    {
    	if (empty($this->_methods)) {
    		$this->_methods = Mage::getBlockSingleton('checkout/onepage')->getMethods();
    	}
    	return $this->_methods;
    }
    
	/**
     * Check and prepare payment method model
     *
     * @return bool
     */
    protected function _canUseMethod($method)
    {
        if (!$method || !$method->canUseCheckout()) {
            return false;
        }
        return parent::_canUseMethod($method);
    }
    
	/**
     * Check existing of payment methods
     *
     * @return bool
     */
    public function hasMethods()
    {
        $methods = $this->getMethods();
        if (is_array($methods) && count($methods)) {
            return true;
        }
        return false;
    }
    
	/**
     * Retrieve code of current payment method
     *
     * @return mixed
     */
	public function getSelectedMethodCode()
    {
        if ($currentMethodCode = $this->getQuote()->getPayment()->getMethod()) {
            return $currentMethodCode;
        }
        if (count($this->getMethods()) == 1) {
            foreach ($this->getMethods() as $method) {
                return $method->getCode();
            }
        }
        return false;
    }
}
