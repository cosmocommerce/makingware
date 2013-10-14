<?php
class Makingware_OrderAdjuster_Model_Mysql4_Reports_Order_Collection extends Mage_Reports_Model_Mysql4_Order_Collection
{
	protected $_orderAdjuster = null;

	protected function getOrderAdjuster()
	{
		if (is_null($this->_orderAdjuster)) {
			$this->_orderAdjuster = Mage::getModel('makingware_orderadjuster/adjuster');
		}
		return $this->_orderAdjuster;
	}

	protected function _getAdjusterExpr($alias = 'adjuster_table')
	{
		empty($alias) && $alias = $this->getTable('makingware_orderadjuster/adjuster');

		static $expr = array();
		if (empty($expr[$alias])) {
			$expr[$alias][] = 0;
			$fields = array_keys($this->getOrderAdjuster()->getEditorFields());
			foreach ($fields as $field) {
				if(substr($field,0,4)!='base'){
					$expr[$alias][] = 'IFNULL(' . $alias . '.' . $field . ', 0)';
				}
	 		}
		}
		return $expr[$alias];
	}

	protected function _setAdjusterLeftJoin($alias = 'adjuster_table', $select = null)
	{
		empty($alias) && $alias = $this->getTable('makingware_orderadjuster/adjuster');
		empty($select) && $select = $this->getSelect();

		$select->joinLeft(
			array('adjuster_table' => $this->getTable('makingware_orderadjuster/adjuster')),
			'main_table.entity_id = ' . $alias . '.order_id'
		);
		return $select;
	}

	public function calculateSales($isFilter = 0)
	{
		parent::calculateSales($isFilter);

        if (! Mage::getStoreConfig('sales/dashboard/use_aggregated_data')) {
        	$columns = $this->getSelect()->getPart(Zend_Db_Select::COLUMNS);
        	if (is_array($columns)) {
	        	$this->_setAdjusterLeftJoin();
	        	foreach ($columns as & $column) {
	        		if (!empty($column[2]) && isset($column[1]) && $column[1] instanceof Zend_Db_Expr) {
	        			if ($column[2] == 'lifetime') {
	        				$column[1] = new Zend_Db_Expr(preg_replace('/SUM\s*\(\s*(.+)\s*\)/i', 'SUM(' . "$1+" . implode('+', $this->_getAdjusterExpr()) . ')', $column[1]->__toString()));
		            	}elseif ($column[2] == 'average') {
		            		$column[1] = new Zend_Db_Expr(preg_replace('/AVG\s*\(\s*(.+)\s*\)/i', 'AVG(' . "$1+" . implode('+', $this->_getAdjusterExpr()) . ')', $column[1]->__toString()));
		            	}
		            }
	        	}
		        $this->getSelect()->setPart(Zend_Db_Select::COLUMNS, $columns);
        	}
        }
	 	return $this;
	}

	protected function _calculateTotalsLive($isFilter = 0)
	{
		parent::_calculateTotalsLive($isFilter);

		$columns = $this->getSelect()->getPart(Zend_Db_Select::COLUMNS);
		if (is_array($columns)) {
			$this->_setAdjusterLeftJoin();
			foreach ($columns as & $column) {
				if (!empty($column[2]) && isset($column[1]) && $column[1] instanceof Zend_Db_Expr) {
					if ($column[2] == 'revenue') {
						$column[1] = new Zend_Db_Expr($column[1]->__toString() . '+ SUM(' . implode('+', $this->_getAdjusterExpr()) . ')');
	            	}
				}
			}
			$this->getSelect()->setPart(Zend_Db_Select::COLUMNS, $columns);
		}
		return $this;
	}

	public function addRevenueToSelect($convertCurrency = false)
    {
    	parent::addRevenueToSelect($convertCurrency);

    	$columns = $this->getSelect()->getPart(Zend_Db_Select::COLUMNS);
    	if (is_array($columns)) {
			foreach ($columns as & $column) {
				if (!empty($column[2]) && isset($column[1]) && $column[1] instanceof Zend_Db_Expr) {
					if ($column[2] == 'revenue') {
						$column[1] = new Zend_Db_Expr(preg_replace('/base_grand_total/i', 'grand_total', $column[1]->__toString()));
	            	}
				}
			}
			$this->getSelect()->setPart(Zend_Db_Select::COLUMNS, $columns);
		}
        return $this;
    }



}