<?php

class Makingware_EnhancedProductManager_Block_Widget_Grid_Column_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
{
    protected $_content = '<div class="editable attribute-%s %s">%s</div>';

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render (Varien_Object $row)
    {
        $data = parent::render($row);
        $options = $this->getColumn()->getOptions();
        $optionsClass = array();
        foreach ($options as $key => $value) {
            $optionsClass[] = "option-{$key}-{$value}";
        }
        $optionsClass = implode(' ', $optionsClass);
        return sprintf($this->_content, $this->getColumn()->getIndex(), $optionsClass, $data);
    }
}
