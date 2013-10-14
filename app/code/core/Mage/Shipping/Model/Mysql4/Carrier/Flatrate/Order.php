<?php

class Mage_Shipping_Model_Mysql4_Carrier_Flatrate_Order extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct ()
    {
        $this->_init('shipping/flatrate_order', 'order_id');
        $this->_isPkAutoIncrement=false;
    }
}