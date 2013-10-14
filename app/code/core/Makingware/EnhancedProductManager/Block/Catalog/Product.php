<?php

class Makingware_EnhancedProductManager_Block_Catalog_Product extends Mage_Adminhtml_Block_Catalog_Product
{
    public function __construct ()
    {
        parent::__construct();
        $this->_headerText = Mage::helper('makingware_enhancedproductmanager')->__(
        	'Manage Products (Enhanced)');
    }
    protected function _prepareLayout ()
    {
        parent::_prepareLayout();
        $this->setTemplate(
        	'makingware/enhancedproductmanager/catalog/product.phtml');
        $this->setChild('grid', 
            $this->getLayout()
            ->createBlock(
        		'makingware_enhancedproductmanager/catalog_product_grid', 
        		'product.enhancedproductmanager')
            );
        $this->setChild('add_new_button', 
            $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(
                array('label' => Mage::helper('catalog')->__('Add Product'), 
        	    	'onclick' => "setLocation('" . $this->getUrl('adminhtml/*/new') . "')", 
                    'class' => 'add'))
        );
    }
}

