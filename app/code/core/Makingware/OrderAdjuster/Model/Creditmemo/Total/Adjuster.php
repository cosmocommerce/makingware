<?php
class Makingware_OrderAdjuster_Model_Creditmemo_Total_Adjuster
{
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
	{
		$adjuster = Mage::getSingleton('makingware_orderadjuster/adjuster')->setOrder($creditmemo->getOrder());
		
		if ($adjuster->getId()) {
			$adjuster->collectTotals();
			$creditmemo->setGrandTotal($adjuster->getOrder()->getGrandTotal());
			$creditmemo->setBaseGrandTotal($adjuster->getOrder()->getGrandTotal());
		}
		
		return $this;
	}
}