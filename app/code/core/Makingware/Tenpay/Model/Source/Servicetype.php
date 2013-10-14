<?php
class Makingware_Tenpay_Model_Source_Servicetype
{
    public function toOptionArray ()
    {
        return array(
            array('value' => 'trade_create_by_buyer', 
        		'label' => Mage::helper('tenpay')->__('Products')), 
            array('value' => 'create_direct_pay_by_user', 
        		'label' => Mage::helper('tenpay')->__('Virtual Products')), 
            array('value' => 'create_partner_trade_by_buyer', 
        		'label' => Mage::helper('tenpay')->__('Trade by Buyer'))
            );
    }
}



