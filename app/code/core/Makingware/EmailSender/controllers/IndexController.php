<?php

class Makingware_EmailSender_IndexController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('makingware_emailsender/index_send'));
		$this->renderLayout();
	}

	public function sendAction()
	{
		$data = array();
		try {
			if ($data = Mage::app()->getRequest()->getPost()) {
				if (empty($data['sender_email']) || empty($data['sender_name']) || 
					empty($data['recipient_email']) || empty($data['recipient_name']) || 
					empty($data['subject']) || empty($data['content'])) {
					Mage::throwException($this->__('Content incomplete'));
				}
				
				Mage::getModel('core/email')->setType('html')
					->setFromEmail($data['sender_email'])->setFromName($data['sender_name'])
					->setToEmail($data['recipient_email'])->setToName($data['recipient_name'])
					->setSubject($data['subject'])->setBody($data['content'])->send();
				
				Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Sent successfully'));
			}
			
		}catch (Exception $ex) {
			Mage::getSingleton('adminhtml/session')
				->setData($data)
				->addError($this->__('Send failed') . ' - ' . $this->__('Reason') . ': ' . $this->__($ex->getMessage()));
		}
		$this->getResponse()->setRedirect($this->getUrl('*/*/'));
	}
}