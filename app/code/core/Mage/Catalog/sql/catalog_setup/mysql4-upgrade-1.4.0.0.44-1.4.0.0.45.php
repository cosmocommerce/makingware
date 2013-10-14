<?php
$installer = $this;
$installer->startSetup();

# 更该 filter_price_range.backend_type 类型  int->varchar, 修改默认值为空('')
$entityTypeId  = $installer->getEntityTypeId('catalog_category');
$attributeId  = $installer->getAttributeId($entityTypeId, 'filter_price_range');

if ($attributeId) {
	$installer->updateAttribute(
		$entityTypeId, 
		$attributeId, 
		array(
			'backend_type' 	=> 'varchar',
			'backend_model'	=> 'catalog/category_attribute_backend_filterpricerange'
		)
	);
	$installer->run("
		DELETE FROM {$installer->getTable('catalog_category_entity_int')} WHERE `entity_type_id` = {$entityTypeId} AND `attribute_id` = {$attributeId};
	");
}

$installer->endSetup();