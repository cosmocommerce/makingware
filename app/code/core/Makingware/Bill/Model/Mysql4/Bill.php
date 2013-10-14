<?php

class Makingware_Bill_Model_Mysql4_Bill extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct ()
    {
        $this->_init('makingware_bill/bill', 'bill_id');
    }
}