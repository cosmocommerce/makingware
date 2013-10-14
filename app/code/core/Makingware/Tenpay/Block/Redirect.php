<?php

class Makingware_Tenpay_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml ()
    {
        $standard = Mage::getModel('tenpay/payment');
        $form = new Varien_Data_Form();
        $form->setAction($standard->getTenpayUrl())
            ->setId('tenpay_checkout')
            ->setName('tenpay_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
            
        foreach ($standard->setOrder($this->getOrder())
            ->getStandardCheckoutFormFields() as $field => $value) {
            $form->addField($field, 'hidden', 
            array('name' => $field, 'value' => $value)
            );
        }
        
        $formHTML = $form->toHtml();
        $html = '<html><body>';
        $html .= $this->__('You will be redirected to Tenpay in a few seconds.');
        $html .= $formHTML;
        $html .= '<script type="text/javascript">document.getElementById("tenpay_checkout").submit();</script>';
        $html .= '</body></html>';
        
        return $html;
    }
}