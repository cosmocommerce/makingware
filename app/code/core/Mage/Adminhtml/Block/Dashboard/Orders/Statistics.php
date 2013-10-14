<?php
class Mage_Adminhtml_Block_Dashboard_Orders_Statistics extends Mage_Adminhtml_Block_Template
{
	protected $_collection = null;
	
	protected function _construct()
	{
		parent::_construct();
		if (!$this->getTemplate()) {
			$this->setTemplate('dashboard/orders/statistics.phtml');
		}
	}
	
	protected function getCollection()
	{
		if (is_null($this->_collection)) {
			$this->_collection = Mage::getResourceModel('sales/order_grid_collection');
		}
		$this->_collection->getSelect()
			->reset()
			->from(array('main_table' => $this->_collection->getMainTable()), 'COUNT(*)');
		return $this->_collection;
	}
	
	protected function waitShipping()
	{
		if (!$this->hasData('wait_shipping')) {
			$collection = $this->getCollection();
			
			$collection->addFieldToFilter('status', array('in' => array('processing', 'payment_review')));
			$select = $collection->getSelect()->where('total_paid = grand_total');
			$this->setData('wait_shipping', $collection->getConnection()->fetchOne($select));
		}
		return $this->getData('wait_shipping');
	}
	
	protected function waitPayment()
	{
		if (!$this->hasData('wait_payment')) {
			$collection = $this->getCollection();
			
			$collection->addAttributeToFilter('status', array('in' => array('processing', 'pending', 'pending_payment')))
				->addFieldToFilter('grand_total', array('gt' => 0))
				->addFieldToFilter(
					array('eq' => 'total_paid', 'null' => 'total_paid'), 
					array('eq' => array('eq' => 0), 'null' => array('null' => true))
				)
			;
			$this->setData('wait_payment', $collection->getConnection()->fetchOne($collection->getSelect()));
		}
		return $this->getData('wait_payment');
	}
	
	protected function holded()
	{
		if (!$this->hasData('holded')) {
			$collection = $this->getCollection();
				
			$collection->addFieldToFilter('status', array('eq' => array('holded')));
			$this->setData('holded', $collection->getConnection()->fetchOne($collection->getSelect()));
		}
		return $this->getData('holded');
	}
	
	protected function completed()
	{
		if (!$this->hasData('completed')) {
			$collection = $this->getCollection();
			
			$collection->addFieldToFilter('status', array('eq' => array('complete')));
			$this->setData('completed', $collection->getConnection()->fetchOne($collection->getSelect()));
		}
		return $this->getData('completed');
	}
}