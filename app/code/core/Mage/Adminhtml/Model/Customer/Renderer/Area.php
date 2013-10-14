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
 * REgion field renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Model_Customer_Renderer_Area implements Varien_Data_Form_Element_Renderer_Interface
{
	/**
	 * Country area collections
	 *
	 * array(
	 *      [$cityId] => Varien_Data_Collection_Db
	 * )
	 *
	 * @var array
	 */
	static protected $_areaCollections;

	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = '<tr>'."\n";

		$cityId = '1';
		if ($city = $element->getForm()->getElement('city_id')) {
			if (!is_null($city->getValue())) {
				$cityId = $city->getValue();
			}
		}

		$areaCollection = false;
		if ($cityId) {
			if (!isset(self::$_areaCollections[$cityId])) {
				self::$_areaCollections[$cityId] = Mage::getModel('directory/city')
					->setId($cityId)
					->getLoadedAreaCollection();
			}
			$areaCollection = self::$_areaCollections[$cityId];
		}

		$areaId = $element->getForm()->getElement('area_id')->getValue();

		$htmlAttributes = $element->getHtmlAttributes();
		foreach ($htmlAttributes as $key => $attribute) {
			if ('type' === $attribute) {
				unset($htmlAttributes[$key]);
				break;
			}
		}
		if ($areaCollection && $areaCollection->getSize()) {
			$elementClass = $element->getClass();
			$element->setClass(str_replace('input-text', '', $elementClass));
			$html.= '<td class="label">'.$element->getLabelHtml().'</td>';
			$html.= '<td class="value"><select id="'.$element->getHtmlId().'" name="'.$element->getName().'" '
				 .$element->serialize($htmlAttributes).'>'."\n";
			foreach ($areaCollection as $area) {
				$selected = ($areaId==$area->getId()) ? ' selected="selected"' : '';
				$html.= '<option value="'.$area->getId().'"'.$selected.'>'.$area->getName().'</option>';
			}
			$html.= '</select>';
			$html.= '<script type="text/javascript">';
			$html.= $this->_getCityAreaUpdaterScript($element->getForm()->getElement('city')->getHtmlId(), $element->getHtmlId(), Mage::helper('directory')->getAreaJson());
			$html.= '</script>';
			$html.= '</td>'."\n";
			$element->setClass($elementClass);
		}
		else {
			$element->setClass('input-text');
			$html.= '<td class="label"><label for="'.$element->getHtmlId().'">'
				. $element->getLabel()
				. ' <span class="required" style="display:none">*</span></label></td>';

			$element->setRequired(false);
			$html.= '<td class="value"><input id="'.$element->getHtmlId().'" name="'.$element->getName()
				 .'" value="'.$element->getEscapedValue().'"'.$element->serialize($htmlAttributes).'/></td>'."\n";
		}
		$html.= '</tr>'."\n";
		return $html;
	}

	protected function _getCityAreaUpdaterScript($cityId, $areaId, $areas)
	{
		return <<<EOT
(function(cityEl, areaEl, areas) {
	var cityEl = $(cityEl);
	var areaEl = $(areaEl);

	cityEl.observe('change', function(event) {
		var element = event.element();
		var value = element.getValue();

		areaEl.options.length = 0;
		if (areas[value] != undefined) {
			for (areaId in areas[value]) {
				area = areas[value][areaId];

				option = document.createElement('OPTION');
				option.value = areaId;
				option.text = area.name;

				if (areaEl.options.add) {
					areaEl.options.add(option);
				} else {
					areaEl.appendChild(option);
				}
			}
			fireEvent(areaEl, 'change');
		}
	});
})('{$cityId}', '{$areaId}', {$areas});
EOT;
	}
}