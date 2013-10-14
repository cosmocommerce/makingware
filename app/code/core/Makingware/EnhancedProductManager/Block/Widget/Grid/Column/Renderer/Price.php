<?php

class Makingware_EnhancedProductManager_Block_Widget_Grid_Column_Renderer_Price extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Price
{
    protected $_content = '<div class="editable attribute-%s">%s</div>';

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render (Varien_Object $row)
    {
        $data = parent::render($row);
        return sprintf($this->_content, $this->getColumn()->getIndex(), $data);
    }
}
