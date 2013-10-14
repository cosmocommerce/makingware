<?php

class Makingware_EnhancedProductManager_Model_Product extends Mage_Catalog_Model_Product
{
    public function isBundle ()
    {
        return $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE;
    }
}
