<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Invoice view  comments form
 *
 * @category    Mage
 * @package     Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Block_Order_Info extends Mage_Core_Block_Template
{
    protected $_links = array();
    protected $_notAllowOnlinePayment = array('free', 'cod','bankremittance');

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sales/order/info.phtml');
    }

    protected function _prepareLayout()
    {
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->__('Order # %s', $this->getOrder()->getRealOrderId()));
        }
        $this->setChild(
            'payment_info',
            $this->helper('payment')->getInfoBlock($this->getOrder()->getPayment())
        );
    }

    public function getPaymentInfoHtml()
    {
        return $this->getChildHtml('payment_info');
    }

    /**
     * Retrieve current order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    public function addLink($name, $path, $label)
    {
        $this->_links[$name] = new Varien_Object(array(
            'name' => $name,
            'label' => $label,
            'url' => empty($path) ? '' : Mage::getUrl($path, array('order_id' => $this->getOrder()->getId()))
        ));
        return $this;
    }

    public function getLinks()
    {
        $this->checkLinks();
        return $this->_links;
    }

    private function checkLinks()
    {
        $order = $this->getOrder();
        if (!$order->hasInvoices()) {
            unset($this->_links['invoice']);
        }
        if (!$order->hasShipments()) {
            unset($this->_links['shipment']);
        }
        if (!$order->hasCreditmemos()) {
            unset($this->_links['creditmemo']);
        }
    }

    public function getReorderUrl($order)
    {
        return $this->getUrl('sales/order/reorder', array('order_id' => $order->getId()));
    }

    public function getPrintUrl($order)
    {
        return $this->getUrl('sales/order/print', array('order_id' => $order->getId()));
    }

    public function getBillInfo()
	{
		$billInfo = Mage::getModel('makingware_bill/bill')->load($this->getOrder()->getId(),'order_id');

		return $billInfo;
	 }
	 
	 public function loadShippingBaseTime()
	 {
	 	if (!$this->hasData('shipping_base_time')) {
	 		$this->setData('shipping_base_time', false);
	 		$shippingCarrier = $this->getOrder()->getShippingCarrier();
	 		if ($shippingCarrier instanceof Mage_Shipping_Model_Carrier_Abstract && $shippingCarrier->getShippingBaseTime()) {
	 			$methodCodes = explode('_',$this->getOrder()->getShippingMethod());
	 			if ($shippingTimeOrderModel = Mage::getModel('shipping/carrier_' . $methodCodes[0] . '_order')) {
	 				$this->setData('shipping_base_time', $shippingTimeOrderModel->load($this->getOrder()->getId()));
	 			}
	 		}
	 		
	 	}
	 	return $this->getData('shipping_base_time');
	 }

	 public function canShowShippingBestTime()
     {
     	return (boolean)$this->loadShippingBaseTime();
     }

     public function getShippingBestTime()
     {
     	if ($shippingBaseTime = $this->loadShippingBaseTime()) {
     		return $shippingBaseTime->getShippingBestTime();
     	}
		return '';
     }

    public function canOnlinePayment($order)
    {
    	return !in_array($order->getPayment()->getMethodInstance()->getCode(), $this->_notAllowOnlinePayment);
    }
    
    public function getOrderStatusDetail($order)
    {
        $message='';
        $invoiceObj= Mage::getResourceModel('sales/order_invoice_grid_collection')->setOrderFilter($order)->load()->getFirstItem();
        $invoiceId= $invoiceObj->getId();
        
        if(!empty($invoiceId)){
            $message.='had paid,';
        }else{
            $message.='had not paid,';
        }
        
        $shipmentObj= Mage::getResourceModel('sales/order_shipment_grid_collection')->setOrderFilter($order)->load()->getFirstItem();
        $shipmentId= $shipmentObj->getId();
        
        if(!empty($shipmentId)){
            $message.='had shipped';
        }else{
            $message.='had not shipped';
        }
        
        return $message;
    }
}
