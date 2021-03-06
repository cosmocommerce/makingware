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
 * Adminhtml shipping agreement view
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Block_Adminhtml_Shipping_Agreement_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize view container
     *
     */
    public function __construct()
    {
        $this->_objectId    = 'agreement';
        $this->_controller  = 'adminhtml_shipping_agreement';
        $this->_mode        = 'view';
        $this->_blockGroup  = 'sales';

        parent::__construct();

        if (!$this->_isAllowed('sales/shipping_agreement/actions/manage')) {
            $this->_removeButton('delete');
        }
        $this->_removeButton('reset');
        $this->_removeButton('save');
        $this->setId('shipping_agreement_view');

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick'   => 'setLocation(\'' . $this->getBackUrl() . '\')',
            'class'     => 'back',
        ), -1);

        if ($this->_getShippingAgreement()->canCancel() && $this->_isAllowed('sales/shipping_agreement/actions/manage')) {
            $this->_addButton('cancel', array(
                'label'     => Mage::helper('adminhtml')->__('Cancel'),
                'onclick'   => "confirmSetLocation('{$this->__('Are you sure you want to do this?')}', '{$this->_getCancelUrl()}')",
                'class'     => 'cancel',
            ), -1);
        }
    }

    /**
     * Retrieve header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        return $this->__('Shipping Agreement #%s', $this->_getShippingAgreement()->getReferenceId());
    }

    /**
     * Retrieve cancel shipping agreement url
     *
     * @return string
     */
    protected function _getCancelUrl()
    {
        return $this->getUrl('*/*/cancel', array('agreement' => $this->_getShippingAgreement()->getAgreementId()));
    }

    /**
     * Retrieve shipping agreement model
     *
     * @return Mage_Sales_Model_Shipping_Agreement
     */
    protected function _getShippingAgreement()
    {
        return Mage::registry('current_shipping_agreement');
    }

    /**
     * Check current user permissions for specified action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowed($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed($action);
    }
}
