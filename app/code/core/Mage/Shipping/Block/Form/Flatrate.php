<?php
class Mage_Shipping_Block_Form_Flatrate extends Mage_Shipping_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('shipping/form/flatrate.phtml');
        parent::_construct();
    }
}
