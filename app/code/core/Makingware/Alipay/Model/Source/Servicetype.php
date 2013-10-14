<?php

class Makingware_Alipay_Model_Source_Servicetype
{
    public function toOptionArray()
    {
        return array(
            array(
            	'value' => 'trade_create_by_buyer',
            	'label' => Mage::helper('alipay')->__('Use both interfaces')
            ),
            array(
            	'value' => 'create_partner_trade_by_buyer',
            	'label' => Mage::helper('alipay')->__('Use guarantee trade interface')
            ),
            array(
            	'value' => 'create_direct_pay_by_user',
            	'label' => Mage::helper('alipay')->__('Use direct pay trade interface')
            )
        );
    }
}



