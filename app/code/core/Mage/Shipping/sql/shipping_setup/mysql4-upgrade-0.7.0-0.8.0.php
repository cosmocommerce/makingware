<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
CREATE TABLE {$this->getTable('shipping_flatrate_order')} (
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `shipping_best_time` varchar(20) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('shipping_flatrate_quote')} (
  `quote_id` int(11) unsigned NOT NULL DEFAULT '0',
  `shipping_best_time` varchar(20) NOT NULL,
  PRIMARY KEY (`quote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$tableName = $installer->getTable('core_config_data');
$scope='default';
$scope_id='0';
$path='carriers/flatrate/shipping_best_time';
$value='只工作日送货(双休日、假日不用送)\r\n工作日、双休日与假日均可送货\r\n只双休日、假日送货(工作日不用送)';
$installer->run("INSERT INTO `{$tableName}` (`scope`, `scope_id`, `path`,`value`) VALUES ('{$scope}', '{$scope_id}', '{$path}','{$value}');");

$installer->endSetup();
