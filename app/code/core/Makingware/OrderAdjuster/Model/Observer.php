<?php
class Makingware_OrderAdjuster_Model_Observer
{
	public function dashboardOrdersGrid(Varien_Event_Observer $observer)
	{
		$routerName = Mage::app()->getRequest()->getRouteName();
		$controllerName = Mage::app()->getRequest()->getControllerName();
		$actionName = Mage::app()->getRequest()->getActionName();

		if ($routerName == 'adminhtml' && $controllerName == 'dashboard' && $actionName == 'index') {
			if (get_class($observer->getEvent()->getBlock()) == 'Mage_Adminhtml_Block_Dashboard_Orders_Grid') {

				var_dump($observer->getEvent()->getBlock()->getColumns());die;

				if ($column = $observer->getEvent()->getBlock()->getColumn('total')) {
					var_dump($column);die;
					$column->setIndex('');
				}
			}
		}

		return $this;
	}

	public function resetAdjusterAmount(Varien_Event_Observer $observer)
	{
		$order=$observer->getEvent()->getCreditmemo()->getOrder();
		$adjuster = Mage::getSingleton('makingware_orderadjuster/adjuster')->setOrder($order);
		$fields=$adjuster->getEditorData();

		foreach ($fields as $code=>$field) {
			if(substr($code,0,4)!='base'){
				$adjuster->{$code} = 0;
			}
		}

		$adjuster->setModifyDate(Mage::getModel('core/date')->date())->save();
	}
}
