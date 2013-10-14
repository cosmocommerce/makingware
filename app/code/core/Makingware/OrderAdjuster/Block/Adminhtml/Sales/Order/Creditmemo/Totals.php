<?php
class Makingware_OrderAdjuster_Block_Adminhtml_Sales_Order_Creditmemo_Totals extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Totals
{
	protected function _initTotals()
	{
		parent::_initTotals();
		$adjuster = Mage::getSingleton('makingware_orderadjuster/adjuster')->setOrder($this->getOrder());

		if ($adjuster->getId()) {
	        $store = Mage::app()->getStore($this->getOrder()->getStoreId());
	        $symbol = Mage::app()->getLocale()->currency($this->getOrder()->getOrderCurrency()->getCode())->getSymbol();

	        foreach ($adjuster->getEditorData() as $code => $value) {
	        	if(substr($code,0,4)=='base'){
	        		 $this->addTotal(
	                 new Varien_Object(array(
	                    'code'      	=> $code,
	                    'is_formated' 	=> true,
	                    'value'	    	=> $symbol . sprintf('%0.2f', $store->roundPrice($value)),
	                    'label'     	=> Mage::helper('makingware_orderadjuster')->__($code),
	                ), 'subtotal')
	             );

	        	}
	        }
		}

		return $this;
	}
}
