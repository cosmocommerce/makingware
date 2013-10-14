<?php
class Makingware_OrderAdjuster_Block_Adminhtml_Sales_Order_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals
{
	protected function _construct()
	{
		parent::_construct();

		if (! $this->getTemplate()) {
			$this->setTemplate('makingware/orderadjuster/sales/order/totals.phtml');
		}
	}

	protected function _initTotals()
	{
		parent::_initTotals();

		$adjuster = Mage::getSingleton('makingware_orderadjuster/adjuster')->setOrder($this->getOrder());
        $store = Mage::app()->getStore($this->getOrder()->getStoreId());
        $symbol = Mage::app()->getLocale()->currency($this->getOrder()->getOrderCurrency()->getCode())->getSymbol();
        $canEditor = $adjuster->canOrderEditor();

        foreach ($adjuster->getEditorData() as $code => $value) {
        	if(substr($code,0,4)=='base'){
        		$value = sprintf('%0.2f', $store->roundPrice($value));
        		if ($canEditor) {
	        		$operator = substr($value, 0, 1);
	        		if (is_numeric($operator)) {
	        			$operator = '+';
	        		}else {
	        			$value = substr($value, 1);
	        		}
        			$value = '<span style="white-space:nowrap;">' .
        				 		'<span class="modify" title="' . $this->helper('makingware_orderadjuster')->__('Click on the modified amount') . '">' . $symbol . $operator . $value . '</span>' .
        				 		'<span class="editor" style="display:none;">' .
        				 			$this->_getOperatorSelectHtml(
        				 			$code, array(
        				 					'+' => $this->helper('makingware_orderadjuster')->__('+'),
        				 					'-' => $this->helper('makingware_orderadjuster')->__('-')
        				 			), $operator) .
        				 			$symbol . '<input type="text" name="' . $code . '" value="' . $value . '" size="10" />' .
        				 			'<button type="button" name="' . $code . '" class="save" style="margin-left:5px;">' .
        				 				'<span>' . $this->helper('makingware_orderadjuster')->__('Confirm') .'</span>' .
        				 			'</button>' .
        				 			'<button type="button" name="' . $code . '" class="cancel" style="margin-left:3px;">' .
        				 				'<span>' . $this->helper('makingware_orderadjuster')->__('Cancel') .'</span>' .
        				 			'</button>' .
        				 		'</span>' .
        					 '</span>';
        		}else {
        			$value = $symbol . $value;
        		}

	            $this->addTotal(
	                new Varien_Object(array(
	                    'code'      	=> $code,
	                    'is_formated' 	=> true,
	                    'value'	    	=> $value,
	                    'label'     	=> $this->helper('makingware_orderadjuster')->__($code),
	                ), 'subtotal')
	             );

        	}

        }

		return $this;
	}

	protected function _getOperatorSelectHtml($name, Array $options, $value)
	{
		$html = '<select name="operator[' . $name . ']">';
		foreach ($options as $val => $text) {
			$selected = $value == $val ? 'selected="selected"' : '';
			$html .= '<option value="' . $val . '" ' . $selected . '>' . $text  . '</option>';
		}
		$html .= '</select>';

		return $html;
	}

	protected function getModifyAdjusterUrl()
	{
		return $this->getUrl('*/*/money', array('_current' => true));
	}
}
