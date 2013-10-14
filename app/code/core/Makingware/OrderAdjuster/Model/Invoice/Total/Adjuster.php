<?php
class Makingware_OrderAdjuster_Model_Invoice_Total_Adjuster
{
	public function collect(Mage_Sales_Model_Order_Invoice $invoice)
	{
		$adjuster = Mage::getSingleton('makingware_orderadjuster/adjuster')->setOrder($invoice->getOrder());
		
		if ($adjuster->getId()) {
			$adjuster->collectTotals();
			$invoice->setGrandTotal($adjuster->getOrder()->getGrandTotal());
			$invoice->setBaseGrandTotal($adjuster->getOrder()->getGrandTotal());
		}
		
		return $this;
	}
}