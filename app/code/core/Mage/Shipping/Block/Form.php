<?php
/**
 * Shipping method form base block
 */
class Mage_Shipping_Block_Form extends Mage_Core_Block_Template
{

    public function getMethod()
    {
        $method = $this->getData('method');

        if (!($method instanceof Mage_Shipping_Model_Carrier_Abstract)) {
            Mage::throwException($this->__('Cannot retrieve the shipping method model object.'));
        }
        return $method;
    }

    public function getInfoData()
    {
    	$modelPath='shipping/carrier_'.$this->getMethodCode().'_quote';
        $quoteModel = Mage::getModel($modelPath);
        $quoteId=$quoteModel->getQuote()->getId();
        $shippingBestTime=$quoteModel->load($quoteId)->getShippingBestTime();

        return $shippingBestTime?$shippingBestTime:'';
    }

     /**
     * Retrieve shipping method code
     *
     * @return string
     */
    public function getMethodCode()
    {
        return $this->getMethod()->getMethodCode();
    }

    protected function _getConfig()
    {
        return Mage::getSingleton('shipping/config');
    }

    public function getShippingBestTime()
    {
        $method = $this->getMethod();
        $allShippingBestTime=$method->getConfigData('shipping_best_time');
        $shippingBestTimes=explode("\n", $allShippingBestTime);
        $shippingBestTimes = array_map('trim', $shippingBestTimes);

        return $shippingBestTimes;
    }
}
