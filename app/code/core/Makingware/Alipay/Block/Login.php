<?php

class Makingware_Alipay_Block_Login extends Mage_Core_Block_Abstract
{
    protected function _toHtml ()
    {
        $r_url = $this->getUrl() . 'alipay/payment/back/';
        $standard = Mage::getModel('alipay/service');
        $html = '<html><body>';
        $html .= $standard->getForm($r_url);
        $html .= '<script type="text/javascript">document.getElementById("alipaysubmit").submit();</script>';
        $html .= '</html></body>';
        return $html;
    }
}