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
 * @package     Mage_Payment
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Mage_Payment_Model_Method_Cod extends Mage_Payment_Model_Method_Abstract
{
	protected $_code        = 'cod';
    protected $_canCod   	= true;
    protected $_formBlockType = 'payment/form_cod';
    protected $_infoBlockType = 'payment/info_cod';

	/**
	 * Assign data to info model instance
	 *
	 * @param   mixed $data
	 * @return  Mage_Payment_Model_Info
	 */
	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setCodType($data->getCodType());
		return $this;
	}

	/**
	 * Prepare info instance for save
	 *
	 * @return Mage_Payment_Model_Abstract
	 */
	public function prepareSave()
	{
		return parent::prepareSave();
	}

	/**
	 * Validate payment method information object
	 *
	 * @param   Mage_Payment_Model_Info $info
	 * @return  Mage_Payment_Model_Abstract
	 */
	public function validate()
	{
		/*
		* calling parent validate function
		*/
		parent::validate();

		$info = $this->getInfoInstance();
		$errorMsg = false;
		$availableTypes = explode(',',$this->getConfigData('codtypes'));
		
		$codeType = $info->getCodType();
		if (!in_array($codeType, $availableTypes)) {
			$errorMsg = $this->_getHelper()->__('Cod type is not allowed for this payment method.');
		}

		if($errorMsg){
			Mage::throwException($errorMsg);
		}

		return $this;
	}

	public function hasVerification()
	{
		$configData = $this->getConfigData('usecodv');
		if(is_null($configData)){
			return true;
		}
		return (bool) $configData;
	}

	/**
	 * Check whether there are CC types set in configuration
	 *
	 * @return bool
	 */
	public function isAvailable($quote = null)
	{
		return $this->getConfigData('codtypes', ($quote ? $quote->getStoreId() : null))
			&& parent::isAvailable($quote);
	}

	/**
	 * Order increment ID getter (either real from order or a reserved from quote)
	 *
	 * @return string
	 */
	private function _getOrderId()
	{
		$info = $this->getInfoInstance();

		if ($this->_isPlaceOrder()) {
			return $info->getOrder()->getIncrementId();
		} else {
			if (!$info->getQuote()->getReservedOrderId()) {
				$info->getQuote()->reserveOrderId();
			}
			return $info->getQuote()->getReservedOrderId();
		}
	}

	/**
	 * Grand total getter
	 *
	 * @return string
	 */
	private function _getAmount()
	{
		$info = $this->getInfoInstance();
		if ($this->_isPlaceOrder()) {
			return (double)$info->getOrder()->getQuoteBaseGrandTotal();
		} else {
			return (double)$info->getQuote()->getBaseGrandTotal();
		}
	}

	/**
	 * Currency code getter
	 *
	 * @return string
	 */
	private function _getCurrencyCode()
	{
		$info = $this->getInfoInstance();

		if ($this->_isPlaceOrder()) {
		return $info->getOrder()->getBaseCurrencyCode();
		} else {
		return $info->getQuote()->getBaseCurrencyCode();
		}
	}

	/**
	 * Whether current operation is order placement
	 *
	 * @return bool
	 */
	private function _isPlaceOrder()
	{
		$info = $this->getInfoInstance();
		if ($info instanceof Mage_Sales_Model_Quote_Payment) {
			return false;
		} elseif ($info instanceof Mage_Sales_Model_Order_Payment) {
			return true;
		}
	}
}
