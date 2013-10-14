<?php

class Makingware_Bill_Block_Bill extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_blockGroup = 'makingware_bill';
    
    public function __construct ()
    {
        $this->_controller = 'bill';
        $this->_headerText = Mage::helper('customer')->__('Manage Bills');
        $this->_addButtonLabel = Mage::helper('customer')->__('Add New Bill');
        parent::__construct();
    }
}