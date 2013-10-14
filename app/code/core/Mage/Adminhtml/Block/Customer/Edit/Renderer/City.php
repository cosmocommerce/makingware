<?php

class Mage_Adminhtml_Block_Customer_Edit_Renderer_City extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	/**
	 * Output the region element and javasctipt that makes it dependent from country element
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string
	 */
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		if ($region = $element->getForm()->getElement('region_id')) {
			$regionId = $region->getValue();
		}
		else {
			return $element->getDefaultHtml();
		}
		
	    $cityId = $element->getForm()->getElement('city_id')->getValue();

		$html = '<tr>';
		$element->setClass('input-text');
		$html.= '<td class="label">'.$element->getLabelHtml().'</td><td class="value">';
		$html.= $element->getElementHtml();

		$selectName = str_replace('city', 'city_id', $element->getName());
		$selectId   = $element->getHtmlId().'_id';
		$html.= '<select id="'.$selectId.'" name="'.$selectName.'" class="select required-entry" style="display:none">';
		$html.= '<option value="">'.Mage::helper('customer')->__('Please select').'</option>';
		$html.= '</select>';

		$html.= '<script type="text/javascript">'."\n";
		$html.= 'new regionUpdater("'.$region->getHtmlId().'", "'.$element->getHtmlId().'", "'.$selectId.'", '.$this->helper('directory')->getCityJson().');'."\n";
		$html.= '</script>'."\n";

		$html.= '</td></tr>'."\n";
		return $html;
	}
}
