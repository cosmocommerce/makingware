<?php
class Mage_Shipping_Model_Observer_Flatrate
{
	public function saveFlatrateQuote(Varien_Event_Observer $observer)
	{
		if($observer->getEvent()->getQuote()->getShippingAddress()->getShippingMethod()=='flatrate_flatrate'){
			$flatrateModel=Mage::getModel('shipping/carrier_flatrate');
			$shippingTime=$flatrateModel->getConfigData('enable_shipping_time');

			if($shippingTime){
				$flatrateQuoteModel = Mage::getModel('shipping/carrier_flatrate_quote');
				$data['shipping_best_time'] = Mage::app()->getRequest()->getPost('shipping_best_time');
				$data['quote_id'] = $observer->getEvent()->getQuote()->getId();

				if (empty($data['shipping_best_time'])){
					$flatrateQuoteModel->load($data['quote_id']);

				}else{
					$flatrateQuoteModel->setData($data)->delete()->save();
				}

				$id = $flatrateQuoteModel->getId();
				if(empty($id)){
					#throw new Exception('Flat rate best time cannot be null');
				}
        	}
		}
	}

	public function saveFlatrateOrder(Varien_Event_Observer $observer)
	{
		$flatrateOrderModel = Mage::getModel('shipping/carrier_flatrate_order');

		if($flatrateOrderModel->getQuote()->getQuote()->getShippingAddress()->getShippingMethod()=='flatrate_flatrate'){
			$flatrateModel=Mage::getModel('shipping/carrier_flatrate');
			$shippingTime=$flatrateModel->getConfigData('enable_shipping_time');

			if($shippingTime){
				if($flatrateOrderModel->getQuote()->getShippingBestTime()){
					$flatrateOrderModel->saveOrder();
        		}
			}
		}
	}
}
