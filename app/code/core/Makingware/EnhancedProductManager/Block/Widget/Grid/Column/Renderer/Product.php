<?php

class Makingware_EnhancedProductManager_Block_Widget_Grid_Column_Renderer_Product extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected $_content = '<div style="float:right"><a href="%s" target="_blank">查看</a></div><div class="editable attribute-%s">%s</div>';

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render (Varien_Object $row)
    {
        return $this->_getValue($row);
    }
    
    protected function _getValue (Varien_Object $row)
    {
        if ($row->getVisibility() == 1) {
            $text = $this->__('Product no visible.');
            $url = "javascript:void(alert('{$text}'));";
        } else {
            $url = $row->getProductUrl();
        }
        $title = $row->getData($this->getColumn()->getIndex());

        return sprintf($this->_content, $url, $this->getColumn()->getIndex(), $title);
    }
}
