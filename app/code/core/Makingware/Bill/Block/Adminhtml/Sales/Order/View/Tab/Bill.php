<?php

class Makingware_Bill_Block_Adminhtml_Sales_Order_View_Tab_Bill extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	public function __construct ()
	{
		parent::__construct();
		$this->setId('order_bills');
		$this->setUseAjax(true);
		Mage::getSingleton('customer/session')->setBillReturnUrl($this->getCurrentUrl());
	}

	protected function _prepareCollection ()
	{
		$collection = Mage::getResourceModel('makingware_bill/bill_collection');
		$collection->setOrderFilter($this->getOrder());
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns ()
	{
		$this->getLayout()
			->getBlock('sales_order_edit')
			->addButton('create_bill', array(
				'label' => Mage::helper('sales')->__('Create Bill'),
				'onclick' => 'setLocation(\'' . $this->getCreateBillUrl() . '\')')
			);

		$this->addColumn('bill_id', array(
			'header' => Mage::helper('makingware_bill')->__('Bill ID'),
			'width' => '50px',
			'index' => 'bill_id',
			'type' => 'number')
		);

		$this->addColumn('increment_id', array(
			'header' => Mage::helper('makingware_bill')->__('Bill Increment ID'),
			'width' => '50px',
			'index' => 'increment_id',
			'type' => 'number'
		));

		$this->addColumn('order_id', array(
			'header' => Mage::helper('makingware_bill')->__('Order ID'),
			'index' => 'order_id',
			'type' => 'number'
		));

		$this->addColumn('invoiced_at', array(
			'header' => Mage::helper('makingware_bill')->__('Billed At'),
			'index' => 'invoiced_at',
			'type' => 'datetime'
		));

		$this->addColumn('status', array(
			'header' => Mage::helper('makingware_bill')->__('Status'),
			'index' => 'status',
			'type' => 'options',
			'options' => array(
				'0' => '未开出',
				'1' => '已开具'
			)
		));

		$this->addColumn('type', array(
			'header' => Mage::helper('makingware_bill')->__('Type'),
			'index' => 'type',
			'type' => 'options',
			'options' => array(
				'0' => '普通发票',
				'1' => '增值税发票'
			)
		));

		$this->addColumn('content', array(
			'header' => Mage::helper('makingware_bill')->__('Content'),
			'index' => 'content',
			'type' => 'options',
			'options' => array(
				'0' => '明细',
				'1' => '办公用品',
				'2' => '电脑配件',
				'3' => '耗材'
			)
		));

		$this->addColumn('title', array(
			'header' => Mage::helper('makingware_bill')->__('Title'),
			'index' => 'title',
			'type'  =>'options',
			'options'=>array(
				'0' => '个人',
				'1' => '单位'
			)
		));

		$this->addColumn('company', array(
			'header' => Mage::helper('makingware_bill')->__('Company'),
			'index' => 'company'
		));

		$this->addColumn('price', array(
			'header' => Mage::helper('makingware_bill')->__('Price'),
			'index' => 'price',
			'type' => 'number'
		));

		$this->addColumn('taxpayer_id', array(
			'header' => Mage::helper('makingware_bill')->__('Tax Payer ID'),
			'index' => 'taxpayer_id',
			'type' => 'number'
		));

		$this->addColumn('phone', array(
			'header' => Mage::helper('makingware_bill')->__('Phone'),
			'index' => 'phone',
			'type' => 'number'
		));

		$this->addColumn('bank', array(
			'header' => Mage::helper('makingware_bill')->__('Bank'),
			'index' => 'bank'
		));

		$this->addColumn('account', array(
			'header' => Mage::helper('makingware_bill')->__('Account'),
			'index' => 'account',
			'type' => 'number'
		));

		$this->addColumn('address', array(
			'header' => Mage::helper('makingware_bill')->__('Address'),
			'index' => 'address'
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('customer')->__('XML'));

		return parent::_prepareColumns();
	}

	/**
	 * Retrieve order model instance
	 *
	 * @return Mage_Sales_Model_Order
	 */
	public function getOrder ()
	{
		return Mage::registry('current_order');
	}

	public function getGridUrl ()
	{
		return $this->getUrl('*/*/invoices', array('_current' => true));
	}

	public function getCreateBillUrl ()
	{
		return $this->getUrl('makingware_bill/index/new', array(
			'order_id' => $this->getOrder()->getId()
		));
	}

	public function getTabLabel ()
	{
		return Mage::helper('sales')->__('Bills');
	}

	public function getTabTitle ()
	{
		return Mage::helper('sales')->__('Order Bills');
	}

	public function canShowTab ()
	{
		return true;
	}

	public function isHidden ()
	{
		return false;
	}
}