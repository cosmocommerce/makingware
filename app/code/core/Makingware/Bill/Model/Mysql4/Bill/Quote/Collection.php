<?php

class Makingware_Bill_Model_Mysql4_Bill_Quote_Collection extends Varien_Data_Collection_Db
{
    protected $_billQuoteTable;
    
    public function __construct ()
    {
        parent::__construct(
        Mage::getSingleton('core/resource')->getConnection('directory_read'));
        $this->_billQuoteTable = Mage::getSingleton('core/resource')->getTableName(
        'makingware_bill/bill_quote');
        $this->_select->from(array('bill_quote' => $this->_billQuoteTable));
        $this->setItemObjectClass(
        Mage::getConfig()->getModelClassName('makingware_bill/bill_quote'));
    }
}