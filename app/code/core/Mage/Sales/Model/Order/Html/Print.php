<?php
class Mage_Sales_Model_Order_Html_Print extends Varien_Object
{
	protected $_items = array();
	
	public function setItem(Mage_Sales_Model_Order_Html_Abstract $item)
	{
		$this->_items[] = $item->filter($item->getTemplate());
		return $this;
	}
	
	public function clearItems()
	{
		$this->_items = array();
		return $this;
	}
	
	public function getItems()
	{
		return $this->_items;
	}
	
	public function render(Mage_Sales_Model_Order_Html_Abstract $object)
	{
		$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>';
		$html .= implode('<div style="page-break-after:always;"></div>', $this->_items);
		$html .= '<script type="text/javascript">window.print();</script>';
		$html .= '</body></html>';
		return $html;
	}
}