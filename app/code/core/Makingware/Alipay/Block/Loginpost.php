<?php

class Makingware_Alipay_Block_Loginpost extends Mage_Core_Block_Abstract
{
    protected function _toHtml ()
    {
        $temp = $_GET["user_id"];
        $firstname = substr($temp, 0, 1);
        $lastname = substr($temp, 1);
        $html = '<html><body>';
        $html .= '<form id="login-form" method="post" action="' .
        $this->getUrl() . 'customer/account/loginPost/">';
        $html .= '<input type="hidden"  id="email" value="' . $_GET["email"] . '" name="login[username]"/>';
        $html .= '<input type="hidden" id="pass" name="login[password]" value="' . $lastname . '"/>';
        $html .= '</form>';
        $html .= '<script type="text/javascript">document.getElementById("login-form").submit();</script>';
        $html .= '</html></body>';
        echo $html;
    }
}