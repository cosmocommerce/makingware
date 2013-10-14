<?php

class Mage_Shipping_Model_Mysql4_Carrier_Flatrate_Quote_Collection extends Varien_Data_Collection_Db
{
    protected $_flatrateQuoteTable;

    public function __construct ()
    {
        parent::__construct(
        Mage::getSingleton('core/resource')->getConnection('directory_read'));
        $this->_flatrateQuoteTable = Mage::getSingleton('core/resource')->getTableName(
        'shipping/flatrate_quote');
        $this->_select->from(array('flatrate_quote' => $this->_flatrateQuoteTable));
        $this->setItemObjectClass(
        Mage::getConfig()->getModelClassName('shipping/flatrate_quote'));
    }
}