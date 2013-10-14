<?php

$installer = $this;
$installer->startSetup();
$setup = $installer->getConnection();

/* Customer Username  */
$this->addAttribute('customer', 'username', array(
    'type' => 'varchar',
    'label' => 'Username',
    'visible' => true,
    'required' => false,
    'input' => 'text'
));

$select = $setup->select()
    ->from($installer->getTable('eav/form_type'), 'type_id')
    ->where('code=?', 'customer_account_create');

$formTypeId = $setup->fetchOne($select);
$entityTypeId = $installer->getEntityTypeId('customer');

$select = $setup->select()
    ->from($installer->getTable('eav/form_fieldset'), 'fieldset_id')
    ->where('type_id=?', $formTypeId)
    ->where('code=?', 'general');

$fieldsetId = $setup->fetchOne($select);

$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id' => $formTypeId,
    'fieldset_id' => $fieldsetId,
    'attribute_id' => $installer->getAttributeId($entityTypeId, 'username'),
    'sort_order' => 2
));

$select = $setup->select()
    ->from($installer->getTable('eav/attribute'), 'attribute_id')
    ->where('attribute_code=?', 'username')
    ->where('entity_type_id=?', $entityTypeId);

$attributeId = $setup->fetchOne($select);

$this->_updateAttributeAdditionalData($entityTypeId, $attributeId, 'sort_order',82);
$this->_updateAttributeAdditionalData($entityTypeId, $attributeId, 'is_system',1);

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'customer_account_create',
    'attribute_id' => $attributeId
));

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'customer_account_edit',
    'attribute_id' => $attributeId
));

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'adminhtml_customer',
    'attribute_id' => $attributeId
));

$this->addAttribute('customer', 'phone', array(
    'type' => 'varchar',
    'label' => 'Phone',
    'visible' => true,
    'required' => false,
    'input' => 'text',
    'sort_order' => 86,
));

$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id' => $formTypeId,
    'fieldset_id' => $fieldsetId,
    'attribute_id' => $installer->getAttributeId($entityTypeId, 'phone'),
    'sort_order' => 3
));

$select = $setup->select()
    ->from($installer->getTable('eav/attribute'), 'attribute_id')
    ->where('attribute_code=?', 'phone')
    ->where('entity_type_id=?', $entityTypeId);

$attributeId = $setup->fetchOne($select);

$this->_updateAttributeAdditionalData($entityTypeId, $attributeId, 'sort_order',83);
$this->_updateAttributeAdditionalData($entityTypeId, $attributeId, 'is_system',1);

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'customer_account_create',
    'attribute_id' => $attributeId
));

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'customer_account_edit',
    'attribute_id' => $attributeId
));

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'adminhtml_customer',
    'attribute_id' => $attributeId
));

$installer->endSetup();