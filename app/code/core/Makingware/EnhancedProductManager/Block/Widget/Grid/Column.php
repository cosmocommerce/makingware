<?php

class Makingware_EnhancedProductManager_Block_Widget_Grid_Column extends Mage_Adminhtml_Block_Widget_Grid_Column
{
    protected function _getRendererByType ()
    {
        switch (strtolower($this->getType())) {
            case 'image':
                $rendererClass = 'makingware_enhancedproductmanager/widget_grid_column_renderer_image';
                break;
            case 'product':
                $rendererClass = 'makingware_enhancedproductmanager/widget_grid_column_renderer_product';
                break;
            case 'price':
                $rendererClass = 'makingware_enhancedproductmanager/widget_grid_column_renderer_price';
                break;
            case 'status':
                $rendererClass = 'makingware_enhancedproductmanager/widget_grid_column_renderer_status';
                break;
            default:
                $rendererClass = parent::_getRendererByType();
                break;
        }
        
        return $rendererClass;
    }
    
    protected function _getFilterByType ()
    {
        switch (strtolower($this->getType())) {
            case 'image':
                $filterClass = 'makingware_enhancedproductmanager/widget_grid_column_filter_image';
                break;
            default:
                $filterClass = parent::_getFilterByType();
                break;
        }
        
        return $filterClass;
    }
}