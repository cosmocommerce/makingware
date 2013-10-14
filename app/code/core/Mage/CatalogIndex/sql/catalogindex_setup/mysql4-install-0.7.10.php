<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_CatalogIndex
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS `{$installer->getTable('catalogindex_eav')}`;
CREATE TABLE `{$installer->getTable('catalogindex_eav')}` (
  `index_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `value` int(11) NOT NULL default '0',
  PRIMARY KEY  (`index_id`),
  KEY `IDX_VALUE` (`value`),
  CONSTRAINT `FK_CATALOGINDEX_EAV_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_EAV_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_EAV_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `{$installer->getTable('catalogindex_price')}`;
CREATE TABLE `{$installer->getTable('catalogindex_price')}` (
  `index_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `customer_group_id` smallint(3) unsigned NOT NULL default '0',
  `qty` decimal(12,4) unsigned NOT NULL default '0.0000',
  `value` decimal(12,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`index_id`),
  KEY `IDX_VALUE` (`value`),
  KEY `IDX_QTY` (`qty`),
  CONSTRAINT `FK_CATALOGINDEX_PRICE_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_PRICE_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_PRICE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_PRICE_CUSTOMER_GROUP` FOREIGN KEY (`customer_group_id`) REFERENCES `{$installer->getTable('customer_group')}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('catalogindex_minimal_price')}` (
  `index_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `customer_group_id` smallint(3) unsigned NOT NULL default '0',
  `qty` decimal(12,4) unsigned NOT NULL default '0.0000',
  `value` decimal(12,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`index_id`),
  KEY `IDX_VALUE` (`value`),
  KEY `IDX_QTY` (`qty`),
  CONSTRAINT `FK_CATALOGINDEX_MINIMAL_PRICE_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_MINIMAL_PRICE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_MINIMAL_PRICE_CUSTOMER_GROUP` FOREIGN KEY (`customer_group_id`) REFERENCES `{$installer->getTable('customer_group')}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('catalogindex_eav_tmp')}` (
	`store_id` smallint(5) unsigned NOT NULL default '0',
	`entity_id` int(10) unsigned NOT NULL default '0',
	`attribute_id` smallint(5) unsigned NOT NULL default '0',
	`value` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `{$installer->getTable('catalogindex_eav_tmp')}`
	select distinct store_id, entity_id, attribute_id, value
	from `{$installer->getTable('catalogindex_eav')}`;

DROP TABLE `{$installer->getTable('catalogindex_eav')}`;

CREATE TABLE `{$installer->getTable('catalogindex_eav')}` (
	`store_id` smallint(5) unsigned NOT NULL default '0',
	`entity_id` int(10) unsigned NOT NULL default '0',
	`attribute_id` smallint(5) unsigned NOT NULL default '0',
	`value` int(11) NOT NULL default '0',
	PRIMARY KEY  (`store_id`,`entity_id`,`attribute_id`,`value`),
	KEY `IDX_VALUE` (`value`),
	KEY `FK_CATALOGINDEX_EAV_ENTITY` (`entity_id`),
	KEY `FK_CATALOGINDEX_EAV_ATTRIBUTE` (`attribute_id`),
	KEY `FK_CATALOGINDEX_EAV_STORE` (`store_id`),
	CONSTRAINT `FK_CATALOGINDEX_EAV_ATTRIBUTE` FOREIGN KEY (`attribute_id`)
		REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_CATALOGINDEX_EAV_ENTITY` FOREIGN KEY (`entity_id`)
		REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_CATALOGINDEX_EAV_STORE` FOREIGN KEY (`store_id`)
		REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `{$installer->getTable('catalogindex_eav')}`
	select store_id, entity_id, attribute_id, value
	from `{$installer->getTable('catalogindex_eav_tmp')}`;

DROP TABLE `{$installer->getTable('catalogindex_eav_tmp')}`;

CREATE TABLE `{$installer->getTable('catalogindex_price_tmp')}` (
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `customer_group_id` smallint(3) unsigned NOT NULL default '0',
  `qty` decimal(12,4) unsigned NOT NULL default '0.0000',
  `value` decimal(12,4) NOT NULL default '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `{$installer->getTable('catalogindex_price_tmp')}`
	select distinct store_id, entity_id, attribute_id, customer_group_id, qty, value
	from `{$installer->getTable('catalogindex_price')}`;

DROP TABLE `{$installer->getTable('catalogindex_price')}`;

CREATE TABLE `{$installer->getTable('catalogindex_price')}` (
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `customer_group_id` smallint(3) unsigned NOT NULL default '0',
  `qty` decimal(12,4) unsigned NOT NULL default '0.0000',
  `value` decimal(12,4) NOT NULL default '0.0000',
  KEY `IDX_VALUE` (`value`),
  KEY `IDX_QTY` (`qty`),
  KEY `FK_CATALOGINDEX_PRICE_ENTITY` (`entity_id`),
  KEY `FK_CATALOGINDEX_PRICE_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOGINDEX_PRICE_STORE` (`store_id`),
  KEY `FK_CATALOGINDEX_PRICE_CUSTOMER_GROUP` (`customer_group_id`),
  KEY `IDX_RANGE_VALUE` (`store_id`, `entity_id`,`attribute_id`,`customer_group_id`,`value`),
  CONSTRAINT `FK_CATALOGINDEX_PRICE_ATTRIBUTE` FOREIGN KEY (`attribute_id`)
	REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_PRICE_ENTITY` FOREIGN KEY (`entity_id`)
	REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOGINDEX_PRICE_STORE` FOREIGN KEY (`store_id`)
	REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `{$installer->getTable('catalogindex_price')}`
	select store_id, entity_id, attribute_id, customer_group_id, qty, value
	from `{$installer->getTable('catalogindex_price_tmp')}`;

DROP TABLE `{$installer->getTable('catalogindex_price_tmp')}`;

CREATE TABLE `{$installer->getTable('catalogindex_aggregation')}` (
    `aggregation_id` int(10) unsigned NOT NULL auto_increment,
    `store_id` smallint(5) unsigned NOT NULL,
    `created_at` datetime NOT NULL,
    `key` varchar(255) default NULL,
    `data` mediumtext,
    PRIMARY KEY  (`aggregation_id`),
    UNIQUE KEY `IDX_STORE_KEY` (`store_id`,`key`),
    CONSTRAINT `FK_CATALOGINDEX_AGGREGATION_STORE` FOREIGN KEY (`store_id`)
        REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('catalogindex_aggregation_tag')}` (
    `tag_id` int(10) unsigned NOT NULL auto_increment,
    `tag_code` varchar(255) NOT NULL,
    PRIMARY KEY  (`tag_id`),
    UNIQUE KEY `IDX_CODE` (`tag_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('catalogindex_aggregation_to_tag')}` (
    `aggregation_id` int(10) unsigned NOT NULL,
    `tag_id` int(10) unsigned NOT NULL,
    UNIQUE KEY `IDX_AGGREGATION_TAG` (`aggregation_id`,`tag_id`),
    KEY `FK_CATALOGINDEX_AGGREGATION_TO_TAG_TAG` (`tag_id`),
    CONSTRAINT `FK_CATALOGINDEX_AGGREGATION_TO_TAG_AGGREGATION` FOREIGN KEY (`aggregation_id`)
        REFERENCES `{$installer->getTable('catalogindex_aggregation')}` (`aggregation_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_CATALOGINDEX_AGGREGATION_TO_TAG_TAG` FOREIGN KEY (`tag_id`)
        REFERENCES `{$installer->getTable('catalogindex_aggregation_tag')}` (`tag_id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->getConnection()->dropForeignKey($installer->getTable('catalogindex_price'), 'FK_CATALOGINDEX_PRICE_CUSTOMER_GROUP');
$installer->run("
TRUNCATE {$installer->getTable('catalogindex_eav')};
TRUNCATE {$installer->getTable('catalogindex_price')};
TRUNCATE {$installer->getTable('catalogindex_minimal_price')};
");

$installer->getConnection()->addConstraint('FK_CATALOGRULE_PRODUCT_PRODUCT',
    $installer->getTable('catalogrule_product'), 'product_id',
    $installer->getTable('catalog_product_entity'), 'entity_id'
);

$installer->getConnection()->addConstraint('FK_CATALOGRULE_PRODUCT_PRICE_PRODUCT',
    $installer->getTable('catalogrule_product_price'), 'product_id',
    $installer->getTable('catalog_product_entity'), 'entity_id'
);
$installer->getConnection()->addColumn($installer->getTable('catalogindex_price'), 'website_id', 'smallint(5) unsigned');
$installer->getConnection()->addColumn($installer->getTable('catalogindex_minimal_price'), 'website_id', 'smallint(5) unsigned');

$installer->convertStoreToWebsite($installer->getTable('catalogindex_minimal_price'));
$installer->convertStoreToWebsite($installer->getTable('catalogindex_price'));
$installer->getConnection()->dropColumn($installer->getTable('catalogindex_price'), 'store_id');
$installer->getConnection()->dropColumn($installer->getTable('catalogindex_minimal_price'), 'store_id');

$installer->getConnection()->addConstraint('FK_CI_PRICE_WEBSITE_ID', $installer->getTable('catalogindex_price'), 'website_id', $installer->getTable('core_website'), 'website_id');
$installer->getConnection()->addConstraint('FK_CI_MINIMAL_PRICE_WEBSITE_ID', $installer->getTable('catalogindex_minimal_price'), 'website_id', $installer->getTable('core_website'), 'website_id');

$installer->getConnection()->addKey($installer->getTable('catalogindex_price'), 'IDX_FULL', array('entity_id', 'attribute_id', 'customer_group_id', 'value', 'website_id'));
$installer->getConnection()->addKey($installer->getTable('catalogindex_minimal_price'), 'IDX_FULL', array('entity_id', 'qty', 'customer_group_id', 'value', 'website_id'));
$installer->endSetup();
