<?php

class Makingware_Alipay_Block_Loginbk extends Mage_Core_Block_Abstract
{
    protected function _toHtml ()
    {
        // exit(var_dump($_GET));
        $temp = $_GET["user_id"];
        $firstname = substr($temp, 0, 1);
        $lastname = substr($temp, 1);
        $html = '<html><body>';
        $html .= '<form id="form_validate" method="post" action="' .
         $this->getUrl() . 'customer/account/createpost/">';
        $html .= '  <input type="hidden" value="" name="success_url"/>';
        $html .= '  <input type="hidden" value="" name="error_url"/>';
        $html .= '  <input type="hidden" value="' . $firstname .
         '" name="firstname" id="firstname"/>';
        $html .= '  <input type="hidden" value="' . $lastname .
         '" name="lastname" id="lastname"/>';
        $html .= '  <input type="hidden" value="' . $_GET["email"] .
         '" id="email_address" name="email"/>';
        $html .= '  <input type="hidden" id="is_subscribed" value="1" name="is_subscribed"/>';
        $html .= '  <input type="hidden" id="password" name="password" value="' .
         $lastname . '"/>';
        $html .= '  <input type="hidden" id="confirmation" name="confirmation" value="' .
         $lastname . '"/>';
        $html .= '</form>';
        $html .= '<script type="text/javascript">document.getElementById("form_validate").submit();</script>';
        $html .= '</html></body>';
        echo $html;
    }
}