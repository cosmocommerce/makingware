<?php
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';

class Makingware_OrderAdjuster_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
	public function moneyAction()
	{
		$order = $this->_initOrder();

		if ($this->getRequest()->isPost() && $order) {
			$adjuster = Mage::getSingleton('makingware_orderadjuster/adjuster')->setOrder($order);

			$postData = $this->getRequest()->getPost();
			$data = array_intersect_key($postData, $adjuster->getEditorFields());

			foreach ($data as $name => $value) {
				if (empty($postData['operator'])) {continue ;}

				$value = trim($value) == '' ? 0 : $postData['operator'] . trim($value);
				if (is_numeric($value)) {
					$adjuster->{$name} = $value;
					$subName = substr($name,5);
 					$adjuster->{$subName} = $value;
				}
			}

			$adjuster->setModifyDate(Mage::getModel('core/date')->date())->save();

			$this->getResponse()->setBody(
            	$this->getLayout()->createBlock('makingware_orderadjuster_adminhtml/sales_order_totals')->setData('ajax')->toHtml()
        	);
        	return ;
		}
		$this->_redirect('*/*/view', array('order_id' => $order->getId()));
	}
}
