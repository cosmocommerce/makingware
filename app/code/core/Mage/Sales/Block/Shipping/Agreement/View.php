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
 * Customer account shipping agreement view block
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Block_Shipping_Agreement_View extends Mage_Core_Block_Template
{
    /**
     * Payment methods array
     *
     * @var array
     */
    protected $_paymentMethods = array();

    /**
     * Shipping Agreement instance
     *
     * @var Mage_Sales_Model_Shipping_Agreement
     */
    protected $_shippingAgreementInstance = null;

    /**
     * Related orders collection
     *
     * @var Mage_Sales_Model_Mysql4_Order_Collection
     */
    protected $_relatedOrders = null;

    /**
     * Retrieve related orders collection
     *
     * @return Mage_Sales_Model_Mysql4_Order_Collection
     */
    public function getRelatedOrders()
    {
        if (is_null($this->_relatedOrders)) {
            $this->_relatedOrders = Mage::getResourceModel('sales/order_collection')
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
                ->addShippingAgreementsFilter($this->_shippingAgreementInstance->getAgreementId())
                ->setOrder('created_at', 'desc');
        }
        return $this->_relatedOrders;
    }

    /**
     * Retrieve order item value by key
     *
     * @param Mage_Sales_Model_Order $order
     * @param string $key
     * @return string
     */
    public function getOrderItemValue(Mage_Sales_Model_Order $order, $key)
    {
        $escape = true;
        switch ($key) {
            case 'order_increment_id':
                $value = $order->getIncrementId();
                break;
            case 'created_at':
                $value = $this->helper('core')->formatDate($order->getCreatedAt(), 'short', true);
                break;
            case 'shipping_address':
                $value = $order->getShippingAddress()
                    ? $this->htmlEscape($order->getShippingAddress()->getName()) : $this->__('N/A');
                break;
            case 'order_total':
                $value = $order->formatPrice($order->getGrandTotal());
                $escape = false;
                break;
            case 'status_label':
                $value = $order->getStatusLabel();
                break;
            case 'view_url':
                $value = $this->getUrl('*/order/view', array('order_id' => $order->getId()));
                break;
            default:
                $value = ($order->getData($key)) ? $order->getData($key) : $this->__('N/A');
        }
        return ($escape) ? $this->escapeHtml($value) : $value;
    }

    /**
     * Set pager
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        if (is_null($this->_shippingAgreementInstance)) {
            $this->_shippingAgreementInstance = Mage::registry('current_shipping_agreement');
        }
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager')
            ->setCollection($this->getRelatedOrders())->setIsOutputRequired(false);
        $this->setChild('pager', $pager);
        $this->getRelatedOrders()->load();

        return $this;
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
     * Set data to block
     *
     * @return string
     */
    protected function _toHtml()
    {
        $this->_loadPaymentMethods();
        $this->setBackUrl($this->getUrl('*/shipping_agreement/'));
        if ($this->_shippingAgreementInstance) {
            $this->setReferenceId($this->_shippingAgreementInstance->getReferenceId());

            $this->setCanCancel($this->_shippingAgreementInstance->canCancel());
            $this->setCancelUrl(
                $this->getUrl('*/shipping_agreement/cancel', array(
                    '_current' => true,
                    'payment_method' => $this->_shippingAgreementInstance->getMethodCode()))
            );

            $paymentMethodTitle = $this->_shippingAgreementInstance->getAgreementLabel();
            $this->setPaymentMethodTitle($paymentMethodTitle);

            $createdAt = $this->_shippingAgreementInstance->getCreatedAt();
            $updatedAt = $this->_shippingAgreementInstance->getUpdatedAt();
            $this->setAgreementCreatedAt(
                ($createdAt) ? $this->helper('core')->formatDate($createdAt, 'short', true) : $this->__('N/A')
            );
            if ($updatedAt) {
                $this->setAgreementUpdatedAt(
                    $this->helper('core')->formatDate($updatedAt, 'short', true)
                );
            }
            $this->setAgreementStatus($this->_shippingAgreementInstance->getStatusLabel());
        }

        return parent::_toHtml();
    }
}
