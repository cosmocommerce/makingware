<?php
$installer = $this;
$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS `{$installer->getTable('bill')}`;
CREATE TABLE `{$installer->getTable('bill')}` (
  `bill_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `increment_id` varchar(50) NOT NULL,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `invoiced_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL,
  `content` tinyint(1) NOT NULL,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `title` varchar(32) NOT NULL DEFAULT '',
  `taxpayer_id` varchar(16) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `bank` varchar(16) DEFAULT NULL,
  `account` varchar(16) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`bill_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `{$installer->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='Bills';

-- DROP TABLE IF EXISTS `{$installer->getTable('bill_quote')}`;
CREATE TABLE `{$installer->getTable('bill_quote')}` (
  `quote_id` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `content` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `title` varchar(32) NOT NULL,
  `taxpayer_id` varchar(16) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `bank` varchar(16) NOT NULL,
  `account` varchar(16) NOT NULL,
  `address` varchar(255) NOT NULL,
  `company` varchar(50) NOT NULL,
  PRIMARY KEY (`quote_id`),
  CONSTRAINT `bill_quote_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `{$installer->getTable('sales/quote')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();