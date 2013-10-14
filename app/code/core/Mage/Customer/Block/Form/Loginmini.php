<?php
class Mage_Customer_Block_Form_Loginmini extends Mage_Customer_Block_Form_Login
{
	protected function _construct()
	{
		parent::_construct();
		if (!$this->getTemplate()) {
			$this->setTemplate('customer/form/login.mini.phtml');
		}
	}
	
	protected function _prepareLayout()
	{
		return $this;
	}
}