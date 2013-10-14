<?php

class Makingware_Bill_Block_Bill_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct ()
	{
		parent::__construct();
		$this->setId('billGrid');
		$this->setUseAjax(true);
		$this->setDefaultSort('bill_id');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection ()
	{
		$collection = Mage::getResourceModel('makingware_bill/bill_collection');
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns ()
	{
		$this->addColumn('bill_id', array(
			'header' => Mage::helper('makingware_bill')->__('Bill ID'),
			'width' => '50px',
			'index' => 'bill_id',
			'type' => 'number'
		));

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
			'header' => Mage::helper('makingware_bill')->__('Invoiced At'),
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
			'index' => 'taxpayer_id'
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

		$this->addColumn('action', array(
			'header' => Mage::helper('makingware_bill')->__('Action'),
			'width' => '100',
			'type' => 'action',
			'getter' => 'getId',
			'actions' => array(
				array(
					'caption' => Mage::helper('makingware_bill')->__('Edit'),
					'url' => array('base' => '*/*/edit'), 'field' => 'id')
				),
			'filter' => false,
			'sortable' => false,
			'index' => 'stores',
			'is_system' => true
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('customer')->__('XML'));

		return parent::_prepareColumns();
	}
	protected function _prepareMassaction ()
	{
		$this->setMassactionIdField('bill_id');
		$this->getMassactionBlock()->setFormFieldName('bill');
		$this->getMassactionBlock()->addItem('delete', array(
			'label' => Mage::helper('makingware_bill')->__('Delete'),
			'url' => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('makingware_bill')->__('Are you sure?')
		));

		return $this;
	}
	public function getGridUrl ()
	{
		return $this->getUrl('*/*/grid', array('_current' => true));
	}
	public function getRowUrl ($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}
