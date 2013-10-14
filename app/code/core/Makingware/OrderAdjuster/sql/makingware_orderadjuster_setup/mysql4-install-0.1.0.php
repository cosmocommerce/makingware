<?php
$installer = $this;

Mage::app()->reinitStores();
$table = $installer->getTable('makingware_orderadjuster/adjuster');

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS `{$table}`;

	CREATE TABLE `{$table}` (
  		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  		`order_id` int(10) unsigned NOT NULL DEFAULT '0',
  		`adjuster_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  		`base_adjuster_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  		`modify_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  		PRIMARY KEY (`id`),
  		UNIQUE KEY `order_id` (`order_id`),
  		CONSTRAINT `order_adjuster_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `sales_flat_order` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
");

$installer->endSetup();
