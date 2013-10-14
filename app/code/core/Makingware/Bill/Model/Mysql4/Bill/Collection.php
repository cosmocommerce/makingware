<?php

class Makingware_Bill_Model_Mysql4_Bill_Collection extends Varien_Data_Collection_Db
{
    protected $_billTable;
    
    public function __construct ()
    {
        parent::__construct(
        Mage::getSingleton('core/resource')->getConnection('directory_read'));
        $this->_billTable = Mage::getSingleton('core/resource')->getTableName(
        'makingware_bill/bill');
        $this->_select->from(array('bill' => $this->_billTable));
        $this->setItemObjectClass(
        Mage::getConfig()->getModelClassName('makingware_bill/bill'));
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