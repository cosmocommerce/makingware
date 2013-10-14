<?php
$installer = $this;
$installer->startSetup();

/**
 * 订单增加  `namespace` 字段
 */
$installer->getConnection()
->addColumn(
	$installer->getTable('sales_flat_order'),
    'namespace',
    'varchar(255) default NULL'
);

$installer->endSetup();