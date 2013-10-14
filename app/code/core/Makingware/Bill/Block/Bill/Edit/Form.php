<?php

class Makingware_Bill_Block_Bill_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm ()
	{
		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => $this->getData('action'),
			'method' => 'post'
		));

		$bill = Mage::registry('current_bill');

		$fieldset = $form->addFieldset('base_fieldset', array(
			'legend' => Mage::helper('makingware_bill')->__('Bill Information')
		));

		$orderIdField = $fieldset->addField('order_id', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Order ID'),
			'required' => false,
			'name' => 'order_id',
			'disabled' => true
		));

		if (! $bill->getId()) {
			$orderIdField->setDisabled(false);
			$orderIdField->setRequired(true);
		}

		$fieldset->addField('invoiced_at', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Invoiced At'),
			'name' => 'invoiced_at'
		));

		$fieldset->addField('status', 'select', array(
			'label' => Mage::helper('makingware_bill')->__('Status'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'status',
			'options' => array(
				'0' => '未开出',
				'1' => '已开具'
			)
		));

		$fieldset->addField('type', 'select', array(
			'label' => Mage::helper('makingware_bill')->__('Type'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'type',
			'options' => array(
				'0' => '普通发票',
				'1' => '增值税发票'
			)
		));

		$fieldset->addField('content','select', array(
			'label' => Mage::helper('makingware_bill')->__('Content'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'content',
			'options' => array(
				'0' => '明细',
				'1' => '办公用品',
				'2' => '电脑配件',
				'3' => '耗材'
			)
		));

		$fieldset->addField('title', 'select',array(
			'label' => Mage::helper('makingware_bill')->__('Title'),
			'class' => 'required-entry',
			'required' => true,
			'options'=>array(
				'0' => '个人',
				'1' => '单位'
			)
		));

		$fieldset->addField('company','text', array(
			'label' => Mage::helper('makingware_bill')->__('Company'),
			'name' => 'company'
		));

		$priceField = $fieldset->addField('price', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Price'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'price'
		));

		$fieldset->addField('taxpayer_id', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Tax Payer ID'),
			'name' => 'taxpayer_id'
		));

		$fieldset->addField('phone', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Phone'),
			'name' => 'phone'
		));

		$fieldset->addField('bank', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Bank'),
			'name' => 'bank'
		));

		$fieldset->addField('account', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Account'),
			'name' => 'account'
		));

		$fieldset->addField('address', 'text', array(
			'label' => Mage::helper('makingware_bill')->__('Address'),
			'name' => 'address'
		));

		$orderId = Mage::app()->getRequest()->getParam('order_id', null);

		if ($bill->getId()) {
			$form->addField('bill_id', 'hidden', array('name' => 'bill_id'));
			$form->setValues($bill->getData());
		} else {
			if (null != $orderId) {
				$orderIdField->setValue($orderId);
				$orderIdField->setReadonly(true);
				$orderModel = Mage::getModel('sales/order')->load($orderId);
				$priceField->setValue($orderModel->getSubtotal());
			}
		}

		$form->setUseContainer(true);
		$this->setForm($form);

		return parent::_prepareForm();
	}
}