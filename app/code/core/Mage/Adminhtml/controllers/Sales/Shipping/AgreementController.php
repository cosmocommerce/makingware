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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml shipping agreement controller
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Sales_Shipping_AgreementController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Shipping agreements
     *
     */
    public function indexAction()
    {
        $this->_title($this->__('Sales'))
            ->_title($this->__('Shipping Agreements'));

        $this->loadLayout()
            ->_setActiveMenu('sales/shipping_agreement')
            ->renderLayout();
    }

    /**
     * Ajax action for shipping agreements
     *
     */
    public function gridAction()
    {
        $this->loadLayout(false)
            ->renderLayout();
    }

    /**
     * View shipping agreement action
     *
     */
    public function viewAction()
    {
        $agreementModel = $this->_initShippingAgreement();

        if ($agreementModel) {
            $this->_title($this->__('Sales'))
                ->_title($this->__('Shipping Agreements'))
                ->_title(sprintf("#%s", $agreementModel->getReferenceId()));

            $this->loadLayout()
                ->_setActiveMenu('sales/shipping_agreement')
                ->renderLayout();
            return;
        }

        $this->_redirect('*/*/');
        return;
    }

    /**
     * Related orders ajax action
     *
     */
    public function ordersGridAction()
    {
        $this->_initShippingAgreement();
        $this->loadLayout(false)
            ->renderLayout();
    }

    /**
     * Cutomer shipping agreements ajax action
     *
     */
    public function customerGridAction()
    {
        $this->_initCustomer();
        $this->loadLayout(false)
            ->renderLayout();
    }

    /**
     * Cancel shipping agreement action
     *
     */
    public function cancelAction()
    {
        $agreementModel = $this->_initShippingAgreement();

        if ($agreementModel && $agreementModel->canCancel()) {
            try {
                $agreementModel->cancel();
                $this->_getSession()->addSuccess($this->__('The shipping agreement has been canceled.'));
                $this->_redirect('*/*/view', array('_current' => true));
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Failed to cancel the shipping agreement.'));
                Mage::logException($e);
            }
            $this->_redirect('*/*/view', array('_current' => true));
        }
        return $this->_redirect('*/*/');
    }

    /**
     * Delete shipping agreement action
     */
    public function deleteAction()
    {
        $agreementModel = $this->_initShippingAgreement();

        if ($agreementModel) {
            try {
                $agreementModel->delete();
                $this->_getSession()->addSuccess($this->__('The shipping agreement has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Failed to delete the shipping agreement.'));
                Mage::logException($e);
            }
            $this->_redirect('*/*/view', array('_current' => true));
        }
        $this->_redirect('*/*/');
    }

    /**
     * Initialize shipping agreement by ID specified in request
     *
     * @return Mage_Sales_Model_Shipping_Agreement | false
     */
    protected function _initShippingAgreement()
    {
        $agreementId = $this->getRequest()->getParam('agreement');
        $agreementModel = Mage::getModel('sales/shipping_agreement')->load($agreementId);

        if (!$agreementModel->getId()) {
            $this->_getSession()->addError($this->__('Wrong shipping agreement ID specified.'));
            return false;
        }

        Mage::register('current_shipping_agreement', $agreementModel);
        return $agreementModel;
    }

    /**
     * Initialize customer by ID specified in request
     *
     * @return Mage_Adminhtml_Sales_Shipping_AgreementController
     */
    protected function _initCustomer()
    {
        $customerId = (int) $this->getRequest()->getParam('id');
        $customer = Mage::getModel('customer/customer');

        if ($customerId) {
            $customer->load($customerId);
        }

        Mage::register('current_customer', $customer);
        return $this;
    }

    /**
     * Retrieve adminhtml session
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'index':
            case 'grid' :
            case 'view' :
                return Mage::getSingleton('admin/session')->isAllowed('sales/shipping_agreement/actions/view');
                break;
            case 'cancel':
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('sales/shipping_agreement/actions/manage');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('sales/shipping_agreement');
                break;
        }
    }
}
