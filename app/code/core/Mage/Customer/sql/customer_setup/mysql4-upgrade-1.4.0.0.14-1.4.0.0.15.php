<?php
$installer = $this;
$installer->startSetup();
$setup = $installer->getConnection();

$entityTypeId = $installer->getEntityTypeId('customer');


if (!$installer->getAttributeId($entityTypeId, 'telephone')) {
	# 更改客户 phone 属性为 telephone
	$attributeId = $setup->fetchOne(
		$setup->select()
			->from($installer->getTable('eav/attribute'), 'attribute_id')
			->where('attribute_code=?', 'phone')
			->where('entity_type_id=?', $entityTypeId)
	);
	$this->_updateAttribute($entityTypeId, $attributeId, 'attribute_code', 'telephone');
}


# 加上客户属性 mobile
$this->addAttribute('customer', 'mobile', array(
    'type' => 'varchar',
    'label' => 'Mobile',
    'visible' => true,
    'required' => false,
    'input' => 'text',
    'sort_order' => 87,
));

$formTypeId = $setup->fetchOne(
	$setup->select()
		->from($installer->getTable('eav/form_type'), 'type_id')
		->where('code=?', 'customer_account_create')
);
$fieldsetId = $setup->fetchOne(
	$setup->select()
	->from($installer->getTable('eav/form_fieldset'), 'fieldset_id')
	->where('type_id=?', $formTypeId)
	->where('code=?', 'general')
);

$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id' => $formTypeId,
    'fieldset_id' => $fieldsetId,
    'attribute_id' => $installer->getAttributeId($entityTypeId, 'mobile'),
    'sort_order' => 3
));

$attributeId = $setup->fetchOne(
	$setup->select()
		->from($installer->getTable('eav/attribute'), 'attribute_id')
		->where('attribute_code=?', 'mobile')
		->where('entity_type_id=?', $entityTypeId)
);

$this->_updateAttributeAdditionalData($entityTypeId, $attributeId, 'sort_order', 84);

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



$entityTypeId = $installer->getEntityTypeId('customer_address');


/**
* 加上客户地址属性 mobile
* eav_entity_type: customer/address
*/
$this->addAttribute('customer_address', 'mobile', array(
    'type' => 'varchar',
    'label' => 'Mobile',
    'visible' => true,
    'required' => false,
    'input' => 'text',
    'sort_order' => 88,
));

$formTypeId = $setup->fetchOne(
	$setup->select()
    	->from($installer->getTable('eav/form_type'), 'type_id')
    	->where('code=?', 'customer_address_edit')
);

$fieldsetId = $setup->fetchOne(
	$setup->select()
    	->from($installer->getTable('eav/form_fieldset'), 'fieldset_id')
    	->where('type_id=?', $formTypeId)
    	->where('code=?', 'address')
);

$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id' => $formTypeId,
    'fieldset_id' => $fieldsetId,
    'attribute_id' => $installer->getAttributeId($entityTypeId, 'mobile'),
    'sort_order' => 3
));

$attributeId = $setup->fetchOne(
	$setup->select()
		->from($installer->getTable('eav/attribute'), 'attribute_id')
		->where('attribute_code=?', 'mobile')
		->where('entity_type_id=?', $entityTypeId)
);

$this->_updateAttributeAdditionalData($entityTypeId, $attributeId, 'sort_order', 28);

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'customer_register_address',
    'attribute_id' => $attributeId
));

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'customer_address_edit',
    'attribute_id' => $attributeId
));

$setup->insert($installer->getTable('customer/form_attribute'), array(
    'form_code' => 'adminhtml_customer_address',
    'attribute_id' => $attributeId
));

$installer->endSetup();