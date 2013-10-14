<?php

class Makingware_EnhancedProductManager_Block_Widget_Grid_Column_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected static $showImagesUrl = null;
    protected static $showByDefault = null;
    protected static $width = null;
    protected static $height = null;
    
    public function __construct ()
    {
        if (self::$showImagesUrl == null) {
            self::$showImagesUrl = (int) Mage::getStoreConfig(
            	'makingware_enhancedproductmanager/images/showurl') === 1;
        }
        
        if (self::$showByDefault == null) {
            self::$showByDefault = (int) Mage::getStoreConfig(
            	'makingware_enhancedproductmanager/images/showbydefault') === 1;
        }
        
        if (self::$width == null) {
            self::$width = Mage::getStoreConfig(
            'makingware_enhancedproductmanager/images/width');
        }
        
        if (self::$height == null) {
            self::$height = Mage::getStoreConfig(
            'makingware_enhancedproductmanager/images/height');
        }
    }
    
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
        $dored = false;
        
        if ($getter = $this->getColumn()->getGetter()) {
            $val = $row->$getter();
        }
        
        $val = $val2 = $row->getData($this->getColumn() ->getIndex());
        $val = str_replace("no_selection", "", $val);
        $val2 = str_replace("no_selection", "", $val2);
        $url = Mage::helper('makingware_enhancedproductmanager')->getImageUrl($val);
        
        if (! Mage::helper('makingware_enhancedproductmanager')->getFileExists($val)) {
            $dored = true;
            $val .= "[!]";
        }
        
        if (strpos($val, "placeholder/")) {
            $dored = true;
        }
        
        $filename = substr($val2, strrpos($val2, "/") + 1, 
        strlen($val2) - strrpos($val2, "/") - 1);
        
        if (! self::$showImagesUrl) {
            $filename = '';
        }
        
        if ($dored) {
            $val = "<span style=\"color:red\" id=\"img\">$filename</span>";
        } else {
            $val = "<span>" . $filename . "</span>";
        }
        
        if (empty($val2)) {
            $out = "<center>" . $this->__("(no image)") . "</center>";
        } else {
            $out = $val . '<center><a href="#" onclick="window.open(\'' . $url .
             '\', \'' . $val2 . '\')"' . 'title="' . $val2 . '" ' . ' url="' .
             $url . '" id="imageurl">';
        }
        
        if (self::$showByDefault && ! empty($val2)) {
            $out .= "<img src=" . $url . " width='" . self::$width . "' ";
            
            if (self::$height > self::$width) {
                $out .= "height='" . self::$height . "' ";
            }
            
            $out .= " />";
        }
        
        $out .= '</a></center>';
        
        return $out;
    }
}
