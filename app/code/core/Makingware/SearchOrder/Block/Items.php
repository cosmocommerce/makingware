<?php

class Makingware_SearchOrder_Block_Items extends Mage_Sales_Block_Items_Abstract
{
    public function getOrder ()
    {
        return Mage::registry('current_order');
    }
}