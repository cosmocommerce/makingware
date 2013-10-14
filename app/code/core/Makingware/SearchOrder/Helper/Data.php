<?php

class Makingware_SearchOrder_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getSearchOrderUrl ()
    {
        return $this->_getUrl('searchorder/order/index');
    }
}
