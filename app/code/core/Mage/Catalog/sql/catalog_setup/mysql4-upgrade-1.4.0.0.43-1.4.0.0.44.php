<?php
    $installer = $this;
    $installer->startSetup();

    $entityTypeId     = $installer->getEntityTypeId('catalog_product');
	$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
	$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

    $installer->addAttribute('catalog_product', 'total_sales',  array(
    'type'              => 'int',
    'group'             => 'General',
    'backend'           => '',
    'frontend'          => '',
    'label'             => '销量',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => true,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'used_in_product_listing' => true,
    'unique'            => false
	));

	$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'total_sales',
    '10'
	);

    $installer->endSetup();