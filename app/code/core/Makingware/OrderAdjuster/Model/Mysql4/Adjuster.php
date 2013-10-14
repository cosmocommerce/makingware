<?php
class Makingware_OrderAdjuster_Model_Mysql4_Adjuster extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct ()
    {
        $this->_init('makingware_orderadjuster/adjuster', 'id');
    }
}