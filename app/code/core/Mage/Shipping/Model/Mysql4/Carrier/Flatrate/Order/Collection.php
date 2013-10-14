<?php

class Mage_Shipping_Model_Mysql4_Carrier_Flatrate_Order_Collection extends Varien_Data_Collection_Db
{
    protected $_flatrateOrderTable;

    public function __construct ()
    {
        parent::__construct(
        Mage::getSingleton('core/resource')->getConnection('directory_read'));
        $this->_flatrateOrderTable = Mage::getSingleton('core/resource')->getTableName(
        'shipping/flatrate_order');
        $this->_select->from(array('flatrate_order' => $this->_flatrateOrderTable));
        $this->setItemObjectClass(
        Mage::getConfig()->getModelClassName('shipping/flatrate_order'));
    }

    public function setOrderFilter ($order)
    {
        if ($order instanceof Mage_Sales_Model_Order) {
            $this->getSelect()->where('order_id = ?', $order->getId());
        } else {
            $this->getSelect()->where('order_id = ?', $order);
        }
        return $this;
    }
}