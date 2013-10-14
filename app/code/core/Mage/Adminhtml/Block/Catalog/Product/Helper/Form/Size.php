<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product form image field helper
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Size extends Varien_Data_Form_Element_Select
{
     public function getElementHtml()
    {
    	//print_r($this->getValue());die;
        $elementAttributeHtml = '';

        if ($this->getReadonly()) {
            $elementAttributeHtml = $elementAttributeHtml . ' readonly="readonly"';
        }

        if ($this->getDisabled()) {
            $elementAttributeHtml = $elementAttributeHtml . ' disabled="disabled"';
        }

         $allOptions=$this->getAllOptions();
         $html = '<select class="required-entry required-entry select" id='.$this->getEntityAttribute()->getAttributeCode().' name=product['.$this->getEntityAttribute()->getAttributeCode().']>';
         $html.='<option value=""></option>';
         foreach($allOptions as $option)
         {
         	 if($this->getValue()==$option["value"])
         	 {
				  $html.='<option selected="true" value='.$option["value"].'>'.$option["label"].'</option>';
         	 }
         	 else
         	 {
				  $html.='<option value='.$option["value"].'>'.$option["label"].'</option>';
         	 }

         }

         $html .= '</select>';

        return $html;
    }

    /**
     * Dublicate interface of Varien_Data_Form_Element_Abstract::setReadonly
     *
     * @param bool $readonly
     * @param bool $useDisabled
     * @return Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Apply
     */
    public function setReadonly($readonly, $useDisabled = false)
    {
        $this->setData('readonly', $readonly);
        $this->setData('disabled', $useDisabled);
        return $this;
    }

    public function getAllOptions()
    {
        if (!is_array($this->_allOptions)) {
            $this->_allOptions = array();
        }

        if (!isset($this->_allOptions)) {
            $collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setPositionOrder('asc')
                ->setAttributeFilter($this->getEntityAttribute()->getId())
                ->setStoreFilter($this->getEntityAttribute()->getStoreId())
                ->load();

            $this->_allOptions = $collection->toOptionArray();
        }

        $options=$this->_allOptions;

        return $options;
    }

}
