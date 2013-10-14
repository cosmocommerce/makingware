<?php

class Makingware_Cms_Model_Adminhtml_Page_Observer
{
    public function prepareForm($observer)
    {
        $form = $observer->getEvent()->getForm();
        $page = Mage::registry('cms_page');
        $identifierPath = '';

        if ($page->getPageId()) {
            $identifierPath = $page->getIdentifierPath();
            $store = Mage::app()->getStore($page->getStoreId());
            $note = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_UNSECURE_URL, $store) . $identifierPath;
            $note = sprintf('<a href="%s" target="_blank">%s</a>', $note, $note);
            $form->getElement('title')->setNote($note);
        }

        $form->getElement('base_fieldset')
        #    ->removeField('identifier')
             ->removeField('store_id');
        $form->addField('store_id', 'hidden', array('name' => 'store'));
        $form->addField('parent_id', 'hidden', array('name' => 'parent'));
        #$form->addField('identifier', 'hidden', array('name' => 'identifier'));

        if ($element = $form->getElement('identifier')) {
            $element->setRequired(false);
            if (!empty($identifierPath)) {
                $element->setNote($element->getNote() . ' ' . $identifierPath);
            }
        }
    }

    public function prepareSave($observer)
    {
        $request = $observer->getEvent()->getRequest();
        $page = $observer->getEvent()->getPage();
        if ($request->has('store')) {
            $page->setStoreId($request->getParam('store'));
        }
        if ($request->has('parent')) {
            $page->setParentId($request->getParam('parent'));
        }
    }
}