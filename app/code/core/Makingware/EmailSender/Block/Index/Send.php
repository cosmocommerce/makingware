<?php

class Makingware_EmailSender_Block_Index_Send extends Mage_Adminhtml_Block_Widget_Form_Container
{	
	public function __construct()
	{
		parent::__construct();

		$this->_mode = 'send';
		$this->_controller = 'index';
		$this->_blockGroup = 'makingware_emailsender';
		$this->_updateButton('save', 'label', $this->__('Send'));
		$this->_removeButton('delete');
		$this->_removeButton('back');
	}
	
	protected function _toHtml()
	{
		$html = '<script type="text/javascript" src="' . $this->getJsUrl('tiny_mce/tiny_mce.js') . '"></script>';
		$html .= '<script type="text/javascript" src="' . $this->getJsUrl('mage/adminhtml/wysiwyg/tiny_mce/setup.js') . '"></script>';
		return $html . parent::_toHtml();
	}

	public function getHeaderText()
	{
		return $this->__('Makingware EmailSender');
	}
}