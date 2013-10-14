<?php

class Makingware_Bill_Block_Bill_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_controller = 'index';
    protected $_blockGroup = 'makingware_bill';

    public function __construct ()
    {
        $this->_objectId = 'id';
        $this->_controller = 'bill';
        parent::__construct();
        $this->_updateButton('save', 'label', Mage::helper('makingware_bill')->__('Save Bill'));
        $this->_updateButton('delete', 'label', Mage::helper('makingware_bill')->__('Delete Bill'));
    }
    
    public function getBillId ()
    {
        return Mage::registry('current_bill')->getId();
    }
    
    public function getHeaderText ()
    {
        return Mage::helper('makingware_bill')->__('Bill Information');
    }
    
    public function getValidationUrl ()
    {
        return $this->getUrl('*/*/validate');
    }
    
    public function getSaveUrl ()
    {
        return $this->getUrl('*/*/save');
    }
}