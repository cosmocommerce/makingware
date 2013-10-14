<?php
class Mage_Adminhtml_Block_Sales_Order_View_Tab_Shipping extends Mage_Adminhtml_Block_Sales_Order_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sales/order/view/tab/shippingtime.phtml');
    }

    /**
     * Retrieve order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

     public function getShippingBestTime()
     {
     	$order=$this->getOrder();
    	$orderId=$order->getId();
    	$shipping_method=$order->getShippingMethod();
    	$methodCodes=explode('_',$shipping_method);
    	$methodCode=$methodCodes[0];
    	$modelPath='shipping/carrier_'.$methodCode.'_order';
		$flatrateOrderModel = Mage::getModel($modelPath)->load($orderId);

		if($flatrateOrderModel){
			return $flatrateOrderModel['shipping_best_time']?$flatrateOrderModel['shipping_best_time']:'';
		}

		return '';
     }
}
