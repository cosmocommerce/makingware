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
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account shipping agreements block
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Block_Shipping_Agreements extends Mage_Core_Block_Template
{
    /**
     * Payment methods array
     *
     * @var array
     */
    protected $_paymentMethods = array();

    /**
     * Shipping agreements collection
     *
     * @var Mage_Sales_Model_Mysql4_Shipping_Agreement_Collection
     */
    protected $_shippingAgreements = null;

    /**
     * Set Shipping Agreement instance
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager')
            ->setCollection($this->getShippingAgreements())->setIsOutputRequired(false);
        $this->setChild('pager', $pager)
            ->setBackUrl($this->getUrl('customer/account/'));
        $this->getShippingAgreements()->load();
        return $this;
    }

    /**
     * Retrieve shipping agreements collection
     *
     * @return Mage_Sales_Model_Mysql4_Shipping_Agreement_Collection
     */
    public function getShippingAgreements()
    {
        if (is_null($this->_shippingAgreements)) {
            $this->_shippingAgreements = Mage::getResourceModel('sales/shipping_agreement_collection')
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomerId())
                ->setOrder('agreement_id', 'desc');
        }
        return $this->_shippingAgreements;
    }

    /**
     * Retrieve item value by key
     *
     * @param Varien_Object $item
     * @param string $key
     * @return mixed
     */
    public function getItemValue(Mage_Sales_Model_Shipping_Agreement $item, $key)
    {
        switch ($key) {
            case 'created_at':
            case 'updated_at':
                $value = ($item->getData($key))
                    ? $this->helper('core')->formatDate($item->getData($key), 'short', true) : $this->__('N/A');
                break;
            case 'edit_url':
                $value = $this->getUrl('*/shipping_agreement/view', array('agreement' => $item->getAgreementId()));
                break;
            case 'payment_method_label':
                $label = $item->getAgreementLabel();
                $value = ($label) ? $label : $this->__('N/A');
                break;
            case 'status':
                $value = $item->getStatusLabel();
                break;
            default:
                $value = ($item->getData($key)) ? $item->getData($key) : $this->__('N/A');
        }
        return $this->escapeHtml($value);
    }

    /**
     * Load available shipping agreement methods
     *
     * @return array
     */
    protected function _loadPaymentMethods()
    {
        if (!$this->_paymentMethods) {
            foreach ($this->helper('payment')->getShippingAgreementMethods() as $paymentMethod) {
                $this->_paymentMethods[$paymentMethod->getCode()] = $paymentMethod->getTitle();
            }
        }
        return $this->_paymentMethods;
    }

    /**
     * Retrieve wizard payment options array
     *
     * @return array
     */
    public function getWizardPaymentMethodOptions()
    {
        $paymentMethodOptions = array();
        foreach ($this->helper('payment')->getShippingAgreementMethods() as $paymentMethod) {
            if ($paymentMethod->getConfigData('allow_shipping_agreement_wizard') == 1) {
                $paymentMethodOptions[$paymentMethod->getCode()] = $paymentMethod->getTitle();
            }
        }
        return $paymentMethodOptions;
    }

    /**
     * Set data to block
     *
     * @return string
     */
    protected function _toHtml()
    {
        $this->setCreateUrl($this->getUrl('*/shipping_agreement/startWizard'));
        return parent::_toHtml();
    }
}
