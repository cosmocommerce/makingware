<?php

class Makingware_EmailSender_Block_Index_Send_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$data = Mage::getSingleton('adminhtml/session')->getData();
		Mage::getSingleton('adminhtml/session')->clear();
		
		$configSenderEmail = $configSenderName = '';
		if ($senders = Mage::getStoreConfig('trans_email/ident_' . Mage::getStoreConfig('contacts/email/sender_email_identity'))) {
			if (is_array($senders)) {
				$configSenderEmail = empty($senders['email']) ? '' : $senders['email'];
				$configSenderName = empty($senders['name']) ? '' : $senders['name'];
			}
		}

		$form = new Varien_Data_Form($data);
		$fieldset = $form->addFieldset('base_fieldset', array('legend'=>$this->__('Send Email'), 'class'=>'fieldset-wide'));
		
		$label = $this->__('Sender Name');
		$fieldset->addField('sender_name', 'text', array(
				'name'  => 'sender_name',
				'label' => $label,
				'title' => $label,
				'required' => true,
				'value' => $form->getSenderName() ? $form->getSenderName() : $configSenderName
			)
		);
		$label = $this->__('Sender Email');
		$fieldset->addField('sender_email', 'text', array(
				'name'  => 'sender_email',
				'label' => $label,
				'title' => $label,
				'required' => true,
				'class' => 'validate-email',
				'value' => $form->getSenderEmail() ? $form->getSenderEmail() : $configSenderEmail
			)
		);

		$label = $this->__('Recipient Name');
		$fieldset->addField('recipient_name', 'text', array(
				'name'  => 'recipient_name',
				'label' => $label,
				'title' => $label,
				'required' => true,
				'value' => $form->getRecipientName()
			)
		);
		$label = $this->__('Recipient Email');
		$fieldset->addField('recipient_email', 'text', array(
				'name'  => 'recipient_email',
				'label' => $label,
				'title' => $label,
				'required' => true,
				'class' => 'validate-email',
				'value' => $form->getRecipientEmail()
			)
		);
		
		$label = $this->__('Email Subject');
		$fieldset->addField('subject', 'text', array(
				'name'  => 'subject',
				'label' => $label,
				'title' => $label,
				'required' => true,
				'value' => $form->getSubject()
			)
		);
		
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('widget_filters' => array('is_email_compatible' => 1)));
        $label = $this->__('Message Content');
        $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'label'     => $label,
            'title'     => $label,
            'required'  => true,
            'state'     => 'html',
            'style'     => 'height:36em;',
            'config'    => $wysiwygConfig,
        	'value' 	=> $form->getContent()
        ));

		$form->setAction($this->getUrl('*/index/send'));
		$form->setMethod('post');
		$form->setUseContainer(true);
		$form->setId('edit_form');

		$this->setForm($form);

		return parent::_prepareForm();
	}
}
