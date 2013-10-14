<?php

class Makingware_Bill_IndexController extends Mage_Adminhtml_Controller_Action
{
	protected function _initBill ($idFieldName = 'id')
	{
		$this->_title($this->__('Bills'))
			->_title($this->__('Manage Bills'));
		$billId = (int) $this->getRequest()->getParam($idFieldName);
		$bill = Mage::getModel('makingware_bill/bill');

		if ($billId) {
			$bill->load($billId);
		}

		Mage::register('current_bill', $bill);
		return $this;
	}

	/**
	 * Bills list action
	 */
	public function indexAction ()
	{
		$this->_title($this->__('Bills'))
			->_title($this->__('Manage Bills'));

		if ($this->getRequest()->getQuery('ajax')) {
			$this->_forward('grid');
			return;
		}

		$this->loadLayout();
		$this->_setActiveMenu('sales');

		$this->_addContent(
			$this->getLayout()
				->createBlock('makingware_bill/bill', 'bill')
		);

		$this->_addBreadcrumb(
			Mage::helper('makingware_bill')->__('Bills'),
			Mage::helper('makingware_bill')->__('Bills')
		);
		$this->_addBreadcrumb(
			Mage::helper('makingware_bill')->__('Manage Bills'),
			Mage::helper('makingware_bill')->__('Manage Bills')
		);

		$this->renderLayout();
	}

	public function gridAction ()
	{
		$this->loadLayout();
		$this->getResponse()->setBody(
			$this->getLayout()
				->createBlock('makingware_bill/bill_grid')
				->toHtml()
		);
	}

	/**
	 * Bill edit action
	 */
	public function editAction ()
	{
		$this->_initBill();
		$this->loadLayout();
		$bill = Mage::registry('current_bill');
		$this->_title(
		$bill->getId() ? $bill->getTitle() : $this->__('New Bill'));
		$this->renderLayout();
	}

	/**
	 * Create new bill action
	 */
	public function newAction ()
	{
		$this->_forward('edit');
	}

	/**
	 * Delete bill action
	 */
	public function deleteAction ()
	{
		$this->_initBill();
		$bill = Mage::registry('current_bill');

		if ($bill->getId()) {
			try {
				$bill->load($bill->getId());
				$bill->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('makingware_bill')->__('Bill was deleted')
				);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(
					$e->getMessage()
				);
			}
		}

		$this->_redirect('*/index');
	}

	/**
	 * Save bill action
	 */
	public function saveAction ()
	{
		if ($data = $this->getRequest()->getPost()) {
			$redirectBack = $this->getRequest()->getParam('back', false);
			$this->_initBill('bill_id');

			/** @var Mage_Bill_Model_Bill */
			$bill = Mage::registry('current_bill');
			$isNewBill = ! $bill->getId();

			try {
				$bill->setData('type', $data['type']);
				$bill->setData('title', $data['title']);
				$bill->setData('price', $data['price']);

				if ($isNewBill) {
					$bill->setData('order_id', $data['order_id']);
				}

				Mage::dispatchEvent('adminhtml_bill_prepare_save', array(
					'bill' => $bill,
					'request' => $this->getRequest()
				));

				$bill->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('makingware_bill')->__('Bill was successfully saved')
				);
				Mage::dispatchEvent('adminhtml_bill_save_after', array(
					'bill' => $bill,
					'request' => $this->getRequest()
				));

				if ($redirectBack) {
					$this->_redirect('*/*/edit', array(
						'id' => $bill->getId()
					));
					return;
				}
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(
				$e->getMessage());
				Mage::getSingleton('adminhtml/session')->setBillData($data);
				$this->getResponse()->setRedirect(
				$this->getUrl('*/*/edit', array('id' => $bill->getId())));
				return;
			}
		}

		$returnUrl = Mage::getSingleton('customer/session')->getBillReturnUrl();

		if ($returnUrl) {
			$this->getResponse()->setRedirect($returnUrl);
		} else {
			$this->getResponse()->setRedirect($this->getUrl('*/*/index'));
		}
	}

	/**
	 * Export bill grid to CSV format
	 */
	public function exportCsvAction ()
	{
		$fileName = 'bills.csv';
		$content = $this->getLayout()
			->createBlock('adminhtml/bill_grid')
			->getCsvFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	/**
	 * Export bill grid to XML format
	 */
	public function exportXmlAction ()
	{
		$fileName = 'bills.xml';
		$content = $this->getLayout()
			->createBlock('adminhtml/bill_grid')
			->getExcelFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	public function validateAction ()
	{
		$response = new Varien_Object();
		$response->setError(0);
		$this->getResponse()->setBody($response->toJson());
	}

	public function massDeleteAction ()
	{
		$billsIds = $this->getRequest()->getParam('bill');

		if (! is_array($billsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(
				Mage::helper('makingware_bill')->__('Please select bill(s)')
			);
		} else {
			try {
				$bill = Mage::getModel('makingware_bill/bill');

				foreach ($billsIds as $billId) {
					$bill->load($billId)->delete();
				}

				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
					count($billsIds))
				);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	protected function _isAllowed ()
	{
		return Mage::getSingleton('admin/session')->isAllowed('makingware_bill/manage');
	}
}