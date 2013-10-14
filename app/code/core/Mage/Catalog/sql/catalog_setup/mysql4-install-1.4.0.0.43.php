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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

if (!$installer->tableExists($installer->getTable('catalog_category_entity'))) {

$installer->run("

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_entity')};
CREATE TABLE {$installer->getTable('catalog_category_entity')} (
  `entity_id` int(10) unsigned NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_set_id` smallint(5) unsigned NOT NULL default '0',
  `parent_id` int(10) unsigned NOT NULL default '0',
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `path` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `children_count` int(11) NOT NULL,
  PRIMARY KEY  (`entity_id`),
  KEY `IDX_LEVEL` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Category Entities';

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_entity_datetime')};
CREATE TABLE {$installer->getTable('catalog_category_entity_datetime')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
   `value` datetime DEFAULT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_BASE` (`entity_type_id`,`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_ATTRIBUTE_DATETIME_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_DATETIME_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_DATETIME_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_DATETIME_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_DATETIME_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_category_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_DATETIME_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_entity_decimal')};
CREATE TABLE {$installer->getTable('catalog_category_entity_decimal')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` decimal(12,4) DEFAULT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_BASE` (`entity_type_id`,`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_ATTRIBUTE_DECIMAL_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_DECIMAL_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_DECIMAL_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_DECIMAL_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_DECIMAL_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_category_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_DECIMAL_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_entity_int')};
CREATE TABLE {$installer->getTable('catalog_category_entity_int')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
   `value` int(11) DEFAULT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_BASE` (`entity_type_id`,`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_ATTRIBUTE_INT_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_CATEGORY_EMTITY_INT_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_CATEGORY_EMTITY_INT_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_CATEGORY_EMTITY_INT_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_EMTITY_INT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_category_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_EMTITY_INT_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_entity_text')};
CREATE TABLE {$installer->getTable('catalog_category_entity_text')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_BASE` (`entity_type_id`,`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_ATTRIBUTE_TEXT_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_TEXT_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_TEXT_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_TEXT_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_TEXT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_category_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_TEXT_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_entity_varchar')};
CREATE TABLE {$installer->getTable('catalog_category_entity_varchar')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_BASE` USING BTREE (`entity_type_id`,`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_ATTRIBUTE_VARCHAR_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_VARCHAR_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_VARCHAR_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_VARCHAR_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_VARCHAR_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_category_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_ENTITY_VARCHAR_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_product')};
CREATE TABLE {$installer->getTable('catalog_category_product')} (
  `category_id` int(10) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `UNQ_CATEGORY_PRODUCT` (`category_id`,`product_id`),
  KEY `CATALOG_CATEGORY_PRODUCT_CATEGORY` (`category_id`),
  KEY `CATALOG_CATEGORY_PRODUCT_PRODUCT` (`product_id`),
  CONSTRAINT `CATALOG_CATEGORY_PRODUCT_CATEGORY` FOREIGN KEY (`category_id`) REFERENCES {$installer->getTable('catalog_category_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_CATEGORY_PRODUCT_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_compare_item')};
CREATE TABLE {$installer->getTable('catalog_compare_item')} (
  `catalog_compare_item_id` int(11) unsigned NOT NULL auto_increment,
  `visitor_id` int(11) unsigned NOT NULL default '0',
  `customer_id` int(11) unsigned default NULL,
  `product_id` int(11) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY  (`catalog_compare_item_id`),
  KEY `FK_CATALOG_COMPARE_ITEM_CUSTOMER` (`customer_id`),
  KEY `FK_CATALOG_COMPARE_ITEM_PRODUCT` (`product_id`),
  KEY `IDX_VISITOR_PRODUCTS` (`visitor_id`,`product_id`),
  KEY `IDX_CUSTOMER_PRODUCTS` (`customer_id`,`product_id`),
  KEY `FK_CATALOG_COMPARE_ITEM_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_COMPARE_ITEM_CUSTOMER` FOREIGN KEY (`customer_id`) REFERENCES {$installer->getTable('customer_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_COMPARE_ITEM_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_COMPARE_ITEM_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_bundle_option')};
CREATE TABLE {$installer->getTable('catalog_product_bundle_option')} (
  `option_id` int(10) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`option_id`),
  KEY `FK_catalog_product_bundle_option` (`product_id`),
  CONSTRAINT `FK_catalog_product_bundle_option` FOREIGN KEY (`product_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_bundle_option_link')};
CREATE TABLE {$installer->getTable('catalog_product_bundle_option_link')} (
  `link_id` int(10) unsigned NOT NULL auto_increment,
  `option_id` int(10) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `discount` decimal(10,4) unsigned default NULL,
  PRIMARY KEY  (`link_id`),
  KEY `FK_catalog_product_bundle_option_link` (`option_id`),
  KEY `FK_catalog_product_bundle_option_link_entity` (`product_id`),
  CONSTRAINT `FK_catalog_product_bundle_option_link` FOREIGN KEY (`option_id`) REFERENCES {$installer->getTable('catalog_product_bundle_option')} (`option_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_catalog_product_bundle_option_link_entity` FOREIGN KEY (`product_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_bundle_option_value')};
CREATE TABLE {$installer->getTable('catalog_product_bundle_option_value')} (
  `value_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `option_id` int(10) unsigned NOT NULL default '0',
  `label` varchar(255) default NULL,
  `position` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`value_id`),
  KEY `FK_catalog_product_bundle_option_label` (`option_id`),
  CONSTRAINT `FK_catalog_product_bundle_option_label` FOREIGN KEY (`option_id`) REFERENCES {$installer->getTable('catalog_product_bundle_option')} (`option_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity')};
CREATE TABLE {$installer->getTable('catalog_product_entity')} (
  `entity_id` int(10) unsigned NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_set_id` smallint(5) unsigned NOT NULL default '0',
  `type_id` varchar(32) NOT NULL DEFAULT 'simple',
  `sku` varchar (64) default NULL,
  `has_options` smallint(1) NOT NULL DEFAULT '0',
  `required_options` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`entity_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_ENTITY_TYPE` (`entity_type_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_ATTRIBUTE_SET_ID` (`attribute_set_id`),
  KEY `sku` (`sku`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_ATTRIBUTE_SET_ID` FOREIGN KEY (`attribute_set_id`) REFERENCES {$installer->getTable('eav_attribute_set')} (`attribute_set_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_ENTITY_TYPE` FOREIGN KEY (`entity_type_id`) REFERENCES {$installer->getTable('eav_entity_type')} (`entity_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Product Entities';

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity_datetime')};
CREATE TABLE {$installer->getTable('catalog_product_entity_datetime')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` datetime DEFAULT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_ATTRIBUTE_VALUE` (`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_DATETIME_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_DATETIME_STORE` (`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_DATETIME_PRODUCT_ENTITY` (`entity_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_DATETIME_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_DATETIME_PRODUCT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_DATETIME_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity_decimal')};
CREATE TABLE {$installer->getTable('catalog_product_entity_decimal')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` decimal(12,4) DEFAULT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_ATTRIBUTE_VALUE` (`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_DECIMAL_STORE` (`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_DECIMAL_PRODUCT_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_DECIMAL_ATTRIBUTE` (`attribute_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_DECIMAL_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_DECIMAL_PRODUCT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_DECIMAL_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity_gallery')};
CREATE TABLE {$installer->getTable('catalog_product_entity_gallery')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `position` int(11) NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_BASE` (`entity_type_id`,`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_ATTRIBUTE_GALLERY_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_GALLERY_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_CATEGORY_ENTITY_GALLERY_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_GALLERY_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_GALLERY_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_GALLERY_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity_int')};
CREATE TABLE {$installer->getTable('catalog_product_entity_int')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` mediumint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_ATTRIBUTE_VALUE` (`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_INT_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_INT_STORE` (`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_INT_PRODUCT_ENTITY` (`entity_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_INT_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_INT_PRODUCT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_INT_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity_text')};
CREATE TABLE {$installer->getTable('catalog_product_entity_text')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` mediumint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_ATTRIBUTE_VALUE` (`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_TEXT_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_TEXT_STORE` (`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_TEXT_PRODUCT_ENTITY` (`entity_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_TEXT_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_TEXT_PRODUCT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_TEXT_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity_tier_price')};
CREATE TABLE {$installer->getTable('catalog_product_entity_tier_price')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_id` int(10) unsigned NOT NULL default '0',
  `all_groups` tinyint (1)unsigned NOT NULL DEFAULT '1',
  `customer_group_id` smallint(5) unsigned NOT NULL default '0',
  `qty` decimal(12,4) NOT NULL default 1,
  `value` decimal(12,4) NOT NULL default '0.0000',
  `website_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`value_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_PRODUCT_ENTITY` (`entity_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_GROUP` (`customer_group_id`),
  KEY `FK_CATALOG_PRODUCT_TIER_WEBSITE` (`website_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_PRODUCT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_GROUP` FOREIGN KEY (`customer_group_id`) REFERENCES {$installer->getTable('customer_group')} (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_TIER_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_entity_varchar')};
CREATE TABLE {$installer->getTable('catalog_product_entity_varchar')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` mediumint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `IDX_ATTRIBUTE_VALUE` (`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_VARCHAR_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_VARCHAR_STORE` (`store_id`),
  KEY `FK_CATALOG_PRODUCT_ENTITY_VARCHAR_PRODUCT_ENTITY` (`entity_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_VARCHAR_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$installer->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_VARCHAR_PRODUCT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_VARCHAR_STORE` FOREIGN KEY (`store_id`) REFERENCES {$installer->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_link')};
CREATE TABLE {$installer->getTable('catalog_product_link')} (
  `link_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL default '0',
  `linked_product_id` int(10) unsigned NOT NULL default '0',
  `link_type_id` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`link_id`),
  KEY `FK_LINK_PRODUCT` (`product_id`),
  KEY `FK_LINKED_PRODUCT` (`linked_product_id`),
  KEY `FK_PRODUCT_LINK_TYPE` (`link_type_id`),
  CONSTRAINT `FK_PRODUCT_LINK_LINKED_PRODUCT` FOREIGN KEY (`linked_product_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_PRODUCT_LINK_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_PRODUCT_LINK_TYPE` FOREIGN KEY (`link_type_id`) REFERENCES {$installer->getTable('catalog_product_link_type')} (`link_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Related products';

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_link_attribute')};
CREATE TABLE {$installer->getTable('catalog_product_link_attribute')} (
  `product_link_attribute_id` smallint(6) unsigned NOT NULL auto_increment,
  `link_type_id` tinyint(3) unsigned NOT NULL default '0',
  `product_link_attribute_code` varchar(32) NOT NULL default '',
  `data_type` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`product_link_attribute_id`),
  KEY `FK_ATTRIBUTE_PRODUCT_LINK_TYPE` (`link_type_id`),
  CONSTRAINT `FK_ATTRIBUTE_PRODUCT_LINK_TYPE` FOREIGN KEY (`link_type_id`) REFERENCES {$installer->getTable('catalog_product_link_type')} (`link_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attributes for product link';

insert  into {$installer->getTable('catalog_product_link_attribute')}(`product_link_attribute_id`,`link_type_id`,`product_link_attribute_code`,`data_type`) values (1,2,'qty','decimal'),(2,1,'position','int'),(3,4,'position','int'),(4,5,'position','int'),(6,1,'qty','decimal'),(7,3,'position','int'),(8,3,'qty','decimal');

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_link_attribute_decimal')};
CREATE TABLE {$installer->getTable('catalog_product_link_attribute_decimal')} (
  `value_id` int(11) unsigned NOT NULL auto_increment,
  `product_link_attribute_id` smallint(6) unsigned default NULL,
  `link_id` int(11) unsigned default NULL,
  `value` decimal(12,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`value_id`),
  KEY `FK_DECIMAL_PRODUCT_LINK_ATTRIBUTE` (`product_link_attribute_id`),
  KEY `FK_DECIMAL_LINK` (`link_id`),
  CONSTRAINT `FK_DECIMAL_LINK` FOREIGN KEY (`link_id`) REFERENCES {$installer->getTable('catalog_product_link')} (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_DECIMAL_PRODUCT_LINK_ATTRIBUTE` FOREIGN KEY (`product_link_attribute_id`) REFERENCES {$installer->getTable('catalog_product_link_attribute')} (`product_link_attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Decimal attributes values';

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_link_attribute_int')};
CREATE TABLE {$installer->getTable('catalog_product_link_attribute_int')} (
  `value_id` int(11) unsigned NOT NULL auto_increment,
  `product_link_attribute_id` smallint(6) unsigned default NULL,
  `link_id` int(11) unsigned default NULL,
  `value` int(11) NOT NULL default '0',
  PRIMARY KEY  (`value_id`),
  KEY `FK_INT_PRODUCT_LINK_ATTRIBUTE` (`product_link_attribute_id`),
  KEY `FK_INT_PRODUCT_LINK` (`link_id`),
  CONSTRAINT `FK_INT_PRODUCT_LINK` FOREIGN KEY (`link_id`) REFERENCES {$installer->getTable('catalog_product_link')} (`link_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_INT_PRODUCT_LINK_ATTRIBUTE` FOREIGN KEY (`product_link_attribute_id`) REFERENCES {$installer->getTable('catalog_product_link_attribute')} (`product_link_attribute_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_link_attribute_varchar')};
CREATE TABLE {$installer->getTable('catalog_product_link_attribute_varchar')} (
  `value_id` int(11) unsigned NOT NULL auto_increment,
  `product_link_attribute_id` smallint(6) unsigned NOT NULL default '0',
  `link_id` int(11) unsigned default NULL,
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`value_id`),
  KEY `FK_VARCHAR_PRODUCT_LINK_ATTRIBUTE` (`product_link_attribute_id`),
  KEY `FK_VARCHAR_LINK` (`link_id`),
  CONSTRAINT `FK_VARCHAR_LINK` FOREIGN KEY (`link_id`) REFERENCES {$installer->getTable('catalog_product_link')} (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_VARCHAR_PRODUCT_LINK_ATTRIBUTE` FOREIGN KEY (`product_link_attribute_id`) REFERENCES {$installer->getTable('catalog_product_link_attribute')} (`product_link_attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Varchar attributes values';

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_link_type')};
CREATE TABLE {$installer->getTable('catalog_product_link_type')} (
  `link_type_id` tinyint(3) unsigned NOT NULL auto_increment,
  `code` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`link_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Types of product link(Related, superproduct, bundles)';

insert  into {$installer->getTable('catalog_product_link_type')}(`link_type_id`,`code`) values (1,'relation'),(2,'bundle'),(3,'super'),(4,'up_sell'),(5,'cross_sell');

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_super_attribute')};
CREATE TABLE {$installer->getTable('catalog_product_super_attribute')} (
  `product_super_attribute_id` int(10) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `position` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`product_super_attribute_id`),
  KEY `FK_SUPER_PRODUCT_ATTRIBUTE_PRODUCT` (`product_id`),
  CONSTRAINT `FK_SUPER_PRODUCT_ATTRIBUTE_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES {$installer->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_super_attribute_label')};
CREATE TABLE {$installer->getTable('catalog_product_super_attribute_label')} (
  `value_id` int(10) unsigned NOT NULL auto_increment,
  `product_super_attribute_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `use_default` tinyint(1) unsigned DEFAULT '0',
  `value` varchar(255) character set utf8 NOT NULL default '',
  PRIMARY KEY  (`value_id`),
  UNIQUE KEY `UNQ_ATTRIBUTE_STORE` (`product_super_attribute_id`,`store_id`),
  KEY `FK_SUPER_PRODUCT_ATTRIBUTE_LABEL` (`product_super_attribute_id`),
  KEY `FK_CATALOG_PRODUCT_SUPER_ATTRIBUTE_LABEL_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_SUPER_ATTRIBUTE_LABEL_ATTRIBUTE` FOREIGN KEY (`product_super_attribute_id`) REFERENCES `{$installer->getTable('catalog_product_super_attribute')}` (`product_super_attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_SUPER_ATTRIBUTE_LABEL_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_super_attribute_pricing')};
CREATE TABLE {$installer->getTable('catalog_product_super_attribute_pricing')} (
  `value_id` int(10) unsigned NOT NULL auto_increment,
  `product_super_attribute_id` int(10) unsigned NOT NULL default '0',
  `value_index` varchar(255) character set utf8 NOT NULL default '',
  `is_percent` tinyint(1) unsigned default '0',
  `pricing_value` decimal(10,4) default NULL,
  `website_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`value_id`),
  KEY `FK_SUPER_PRODUCT_ATTRIBUTE_PRICING` (`product_super_attribute_id`),
  KEY `FK_CATALOG_PRODUCT_SUPER_PRICE_WEBSITE` (`website_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_SUPER_PRICE_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core/website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SUPER_PRODUCT_ATTRIBUTE_PRICING` FOREIGN KEY (`product_super_attribute_id`) REFERENCES `{$installer->getTable('catalog_product_super_attribute')}` (`product_super_attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_super_link')};
CREATE TABLE {$installer->getTable('catalog_product_super_link')} (
  `link_id` int(10) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL default '0',
  `parent_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`link_id`),
  KEY `FK_SUPER_PRODUCT_LINK_PARENT` (`parent_id`),
  KEY `FK_catalog_product_super_link` (`product_id`),
  CONSTRAINT `FK_SUPER_PRODUCT_LINK_ENTITY` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SUPER_PRODUCT_LINK_PARENT` FOREIGN KEY (`parent_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_category_product_index')};
CREATE TABLE `{$installer->getTable('catalog_category_product_index')}` (
  `category_id` int(10) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  `is_parent` tinyint(1) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `visibility` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `UNQ_CATEGORY_PRODUCT` (`category_id`,`product_id`,`is_parent`,`store_id`),
  KEY `FK_CATALOG_CATEGORY_PRODUCT_INDEX_CATEGORY_ENTITY` (`category_id`),
  KEY `IDX_JOIN` (`product_id`,`store_id`,`category_id`,`visibility`),
  KEY `IDX_BASE` (`store_id`,`category_id`,`visibility`,`is_parent`,`position`),
  CONSTRAINT `FK_CATALOG_CATEGORY_PRODUCT_INDEX_PRODUCT_ENTITY` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_CATEGORY_PRODUCT_INDEX_CATEGORY_ENTITY` FOREIGN KEY (`category_id`) REFERENCES `{$installer->getTable('catalog_category_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATEGORY_PRODUCT_INDEX_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_enabled_index')};
CREATE TABLE `{$installer->getTable('catalog_product_enabled_index')}` (
  `product_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `visibility` smallint(5) unsigned NOT NULL default '0',
  UNIQUE KEY `UNQ_PRODUCT_STORE` (`product_id`,`store_id`),
  KEY `IDX_PRODUCT_VISIBILITY_IN_STORE` (`product_id`,`store_id`, `visibility`),
  CONSTRAINT `FK_CATALOG_PRODUCT_ENABLED_INDEX_PRODUCT_ENTITY` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_ENABLED_INDEX_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

update {$installer->getTable('eav_entity_attribute')} set `sort_order`=10 where `attribute_id`=(select `attribute_id` from {$installer->getTable('eav_attribute')} where `attribute_code`='tier_price');

-- DROP TABLE IF EXISTS {$installer->getTable('catalog_product_website')};
CREATE TABLE {$installer->getTable('catalog_product_website')} (
  `product_id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `website_id` SMALLINT(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`, `website_id`),
  KEY `FK_CATALOG_PRODUCT_WEBSITE_WEBSITE` (`website_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_WEBSITE_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core/website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_WEBSITE_PRODUCT_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT = FIXED;

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog_product_entity_media_gallery')}`;
CREATE TABLE `{$installer->getTable('catalog_product_entity_media_gallery')}` (
  `value_id` int(11) unsigned NOT NULL auto_increment,
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`value_id`),
  KEY `FK_CATALOG_PRODUCT_MEDIA_GALLERY_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_PRODUCT_MEDIA_GALLERY_ENTITY` (`entity_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_GALLERY_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_GALLERY_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalog product media gallery';

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog_product_entity_media_gallery_value')}`;
CREATE TABLE `{$installer->getTable('catalog_product_entity_media_gallery_value')}` (
  `value_id` int(11) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `label` varchar(255) default NULL,
  `position` int(11) unsigned default NULL,
  `disabled` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`value_id`,`store_id`),
  KEY `FK_CATALOG_PRODUCT_MEDIA_GALLERY_VALUE_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_GALLERY_VALUE_GALLERY` FOREIGN KEY (`value_id`) REFERENCES `{$installer->getTable('catalog_product_entity_media_gallery')}` (`value_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_GALLERY_VALUE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalog product media gallery values';

");

$installer->getConnection()->dropColumn($installer->getTable('eav_attribute'), 'use_in_super_product');

$installer->getConnection()->addColumn($installer->getTable('core_url_rewrite'), 'category_id', 'int unsigned NULL AFTER `store_id`');
$installer->getConnection()->addColumn($installer->getTable('core_url_rewrite'), 'product_id', 'int unsigned NULL AFTER `category_id`');
$installer->getConnection()->addConstraint('FK_CORE_URL_REWRITE_CATEGORY', $installer->getTable('core_url_rewrite'), 'category_id', $installer->getTable('catalog_category_entity'), 'entity_id');
$installer->getConnection()->addConstraint('FK_CORE_URL_REWRITE_PRODUCT', $installer->getTable('core_url_rewrite'), 'product_id', $installer->getTable('catalog_product_entity'), 'entity_id');

$installer->run("
UPDATE `{$installer->getTable('eav_attribute')}` SET `position` = 1 WHERE `position` = 0 AND `attribute_code` != 'price';

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_option')}`;
CREATE TABLE `{$installer->getTable('catalog/product_option')}` (
  `option_id` int(10) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `is_require` tinyint(1) NOT NULL default '1',
  `sku` varchar(64) NOT NULL default '',
  `max_characters` int(10) unsigned default NULL,
  `file_extension` varchar(50) default NULL,
  `image_size_x` smallint(5) unsigned NOT NULL,
  `image_size_y` smallint(5) unsigned NOT NULL,
  `sort_order` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`option_id`),
  KEY `CATALOG_PRODUCT_OPTION_PRODUCT` (`product_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB default CHARSET=utf8;

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_option_price')}`;
CREATE TABLE `{$installer->getTable('catalog/product_option_price')}` (
  `option_price_id` int(10) unsigned NOT NULL auto_increment,
  `option_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `price` decimal(12,4) NOT NULL default '0.00',
  `price_type` enum('fixed', 'percent') NOT NULL default 'fixed',
  PRIMARY KEY (`option_price_id`),
  KEY `CATALOG_PRODUCT_OPTION_PRICE_OPTION` (`option_id`),
  KEY `CATALOG_PRODUCT_OPTION_TITLE_STORE` (`store_id`),
  KEY `IDX_CATALOG_PRODUCT_OPTION_PRICE_SI_OI` (`store_id`,`option_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_PRICE_OPTION` FOREIGN KEY (`option_id`) REFERENCES `{$installer->getTable('catalog/product_option')}` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_PRICE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB default CHARSET=utf8;

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_option_title')}`;
CREATE TABLE `{$installer->getTable('catalog/product_option_title')}` (
  `option_title_id` int(10) unsigned NOT NULL auto_increment,
  `option_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `title` VARCHAR(255) NOT NULL default '',
  PRIMARY KEY (`option_title_id`),
  KEY `CATALOG_PRODUCT_OPTION_TITLE_OPTION` (`option_id`),
  KEY `CATALOG_PRODUCT_OPTION_TITLE_STORE` (`store_id`),
  KEY `IDX_CATALOG_PRODUCT_OPTION_TITLE_SI_OI` (`store_id`,`option_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_TITLE_OPTION` FOREIGN KEY (`option_id`) REFERENCES `{$installer->getTable('catalog/product_option')}` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_TITLE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB default CHARSET=utf8;

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_option_type_value')}`;
CREATE TABLE `{$installer->getTable('catalog/product_option_type_value')}` (
  `option_type_id` int(10) unsigned NOT NULL auto_increment,
  `option_id` int(10) unsigned NOT NULL default '0',
  `sku` varchar(64) NOT NULL default '',
  `sort_order` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`option_type_id`),
  KEY `CATALOG_PRODUCT_OPTION_TYPE_VALUE_OPTION` (`option_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_TYPE_VALUE_OPTION` FOREIGN KEY (`option_id`) REFERENCES `{$installer->getTable('catalog/product_option')}` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB default CHARSET=utf8;

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_option_type_price')}`;
CREATE TABLE `{$installer->getTable('catalog/product_option_type_price')}` (
  `option_type_price_id` int(10) unsigned NOT NULL auto_increment,
  `option_type_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `price` decimal(12,4) NOT NULL default '0.00',
  `price_type` enum('fixed','percent') NOT NULL default 'fixed',
  PRIMARY KEY (`option_type_price_id`),
  KEY `CATALOG_PRODUCT_OPTION_TYPE_PRICE_OPTION_TYPE` (`option_type_id`),
  KEY `CATALOG_PRODUCT_OPTION_TYPE_PRICE_STORE` (`store_id`),
  KEY `IDX_CATALOG_PRODUCT_OPTION_TYPE_PRICE_SI_OTI` (`store_id`,`option_type_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_TYPE_PRICE_OPTION` FOREIGN KEY (`option_type_id`) REFERENCES `{$installer->getTable('catalog/product_option_type_value')}` (`option_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_TYPE_PRICE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB default CHARSET=utf8;

-- DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_option_type_title')}`;
CREATE TABLE `{$installer->getTable('catalog/product_option_type_title')}` (
  `option_type_title_id` int(10) unsigned NOT NULL auto_increment,
  `option_type_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY (`option_type_title_id`),
  KEY `CATALOG_PRODUCT_OPTION_TYPE_TITLE_OPTION` (`option_type_id`),
  KEY `CATALOG_PRODUCT_OPTION_TYPE_TITLE_STORE` (`store_id`),
  KEY `IDX_CATALOG_PRODUCT_OPTION_TYPE_TITLE_SI_OTI` (`store_id`,`option_type_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_TYPE_TITLE_OPTION` FOREIGN KEY (`option_type_id`) REFERENCES `{$installer->getTable('catalog/product_option_type_value')}` (`option_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_OPTION_TYPE_TITLE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB default CHARSET=utf8;


ALTER TABLE `{$installer->getTable('core_url_rewrite')}` ADD INDEX `IDX_CATEGORY_REWRITE` (`category_id`, `is_system`, `product_id`, `store_id`, `id_path`);
");

$groupedLinkType = Mage_Catalog_Model_Product_Link::LINK_TYPE_GROUPED;
//我的
$installer->run("
CREATE TABLE `{$installer->getTable('catalog/eav_attribute')}` (
  `attribute_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `frontend_input_renderer` varchar(255) DEFAULT NULL,
  `is_global` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_searchable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_filterable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_comparable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_visible_on_front` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_html_allowed_on_front` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_used_for_price_rules` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deprecated after 1.4.0.1',
  `is_filterable_in_search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `used_in_product_listing` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `used_for_sort_by` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_configurable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `apply_to` varchar(255) NOT NULL,
  `is_visible_in_advanced_search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL,
  `is_wysiwyg_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_used_for_promo_rules` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`attribute_id`),
  KEY `IDX_USED_FOR_SORT_BY` (`used_for_sort_by`),
  KEY `IDX_USED_IN_PRODUCT_LISTING` (`used_in_product_listing`),
  CONSTRAINT `FK_CATALOG_EAV_ATTRIBUTE_ID` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav/attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `{$installer->getTable('catalog/product_relation')}` (
  `parent_id` INT(10) UNSIGNED NOT NULL,
  `child_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY  (`parent_id`,`child_id`),
  KEY `IDX_CHILD` (`child_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_RELATION_CHILD` FOREIGN KEY (`child_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_RELATION_PARENT` FOREIGN KEY (`parent_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

INSERT IGNORE INTO `{$installer->getTable('catalog/product_relation')}`
SELECT
  `product_id`,
  `linked_product_id`
FROM `{$installer->getTable('catalog/product_link')}`
WHERE `link_type_id`={$groupedLinkType};

INSERT IGNORE INTO `{$installer->getTable('catalog/product_relation')}`
SELECT
  `parent_id`,
  `product_id`
FROM `{$installer->getTable('catalog/product_super_link')}`;

CREATE TABLE `{$installer->getTable('catalog/product_index_eav')}` (
  `entity_id` int(10) unsigned NOT NULL,
  `attribute_id` smallint(5) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  PRIMARY KEY (`entity_id`,`attribute_id`,`store_id`,`value`),
  KEY `IDX_ENTITY` (`entity_id`),
  KEY `IDX_ATTRIBUTE` (`attribute_id`),
  KEY `IDX_STORE` (`store_id`),
  KEY `IDX_VALUE` (`value`),
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_EAV_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav/attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_EAV_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_EAV_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('catalog/product_index_price')}` (
  `entity_id` INT(10) UNSIGNED NOT NULL,
  `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
  `website_id` SMALLINT(5) UNSIGNED NOT NULL,
  `price` DECIMAL(12,4) DEFAULT NULL,
  `final_price` decimal(12,4) DEFAULT NULL,
  `min_price` DECIMAL(12,4) DEFAULT NULL,
  `max_price` DECIMAL(12,4) DEFAULT NULL,
  `tier_price` decimal(12,4) DEFAULT NULL,
  PRIMARY KEY  (`entity_id`,`customer_group_id`,`website_id`),
  KEY `IDX_CUSTOMER_GROUP` (`customer_group_id`),
  KEY `IDX_WEBSITE` (`website_id`),
  KEY `IDX_MIN_PRICE` (`min_price`),
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_PRICE_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core/website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_PRICE_CUSTOMER_GROUP` FOREIGN KEY (`customer_group_id`) REFERENCES `{$installer->getTable('customer/customer_group')}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_PRICE_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('catalog/product_index_eav_decimal')}` (
  `entity_id` int(10) unsigned NOT NULL,
  `attribute_id` smallint(5) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `value` decimal(12, 4) NOT NULL,
  PRIMARY KEY (`entity_id`,`attribute_id`,`store_id`,`value`),
  KEY `IDX_ENTITY` (`entity_id`),
  KEY `IDX_ATTRIBUTE` (`attribute_id`),
  KEY `IDX_STORE` (`store_id`),
  KEY `IDX_VALUE` (`value`),
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_EAV_DECIMAL_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav/attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_EAV_DECIMAL_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_EAV_DECIMAL_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Removing old index tables if exists
DROP TABLE IF EXISTS `{$installer->getTable('catalog/product')}_tier_price_idx`;
DROP TABLE IF EXISTS `{$installer->getTable('core/website')}_date_idx`;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('catalog/product_index_tier_price')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY  (`entity_id`,`customer_group_id`,`website_id`),
    CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_TIER_PRICE_ENTITY` FOREIGN KEY (`entity_id`)
    REFERENCES `{$this->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_TIER_PRICE_CUSTOMER` FOREIGN KEY (`customer_group_id`)
    REFERENCES `{$this->getTable('customer/customer_group')}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_TIER_PRICE_WEBSITE` FOREIGN KEY (`website_id`)
    REFERENCES `{$this->getTable('core/website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('catalog/product_index_website')}` (
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `date` DATE DEFAULT NULL,
    `rate` FLOAT(12, 4) UNSIGNED DEFAULT 1,
    PRIMARY KEY (`website_id`),
    KEY `IDX_DATE` (`date`),
    CONSTRAINT `FK_CATALOG_PRODUCT_INDEX_WEBSITE` FOREIGN KEY (`website_id`)
    REFERENCES `{$this->getTable('core/website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_idx_cfg_opt_aggregate`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_cfg_option_aggregate_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_cfg_option_aggregate_idx')}` (
    `parent_id` int(10) unsigned NOT NULL,
    `child_id` int(10) unsigned NOT NULL,
    `customer_group_id` smallint(5) unsigned NOT NULL,
    `website_id` smallint(5) unsigned NOT NULL,
    `price` decimal(12,4) default NULL,
    `tier_price` decimal(12,4) default NULL,
    PRIMARY KEY  (`parent_id`,`child_id`,`customer_group_id`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_cfg_option_aggregate_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_cfg_option_aggregate_tmp')}` (
     `entity_id` int(10) unsigned NOT NULL,
    `customer_group_id` smallint(5) unsigned NOT NULL,
    `website_id` smallint(5) unsigned NOT NULL,
    `min_price` decimal(12,4) default NULL,
    `max_price` decimal(12,4) default NULL,
    PRIMARY KEY  (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_idx_cfg_option`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_cfg_option_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_cfg_option_idx')}` (
    `entity_id` int(10) unsigned NOT NULL,
    `customer_group_id` smallint(5) unsigned NOT NULL,
    `website_id` smallint(5) unsigned NOT NULL,
    `min_price` decimal(12,4) default NULL,
    `max_price` decimal(12,4) default NULL,
    `tier_price` decimal(12,4) default NULL,
    PRIMARY KEY  (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_cfg_option_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_cfg_option_tmp')}` (
    `entity_id` int(10) unsigned NOT NULL,
    `customer_group_id` smallint(5) unsigned NOT NULL,
    `website_id` smallint(5) unsigned NOT NULL,
    `min_price` decimal(12,4) default NULL,
    `max_price` decimal(12,4) default NULL,
    `tier_price` decimal(12,4) default NULL,
    PRIMARY KEY  (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_final_idx`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_final_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_final_idx')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `orig_price` DECIMAL(12,4) DEFAULT NULL,
    `price` DECIMAL(12,4) DEFAULT NULL,
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    `base_tier` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_final_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_final_tmp')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `orig_price` DECIMAL(12,4) DEFAULT NULL,
    `price` DECIMAL(12,4) DEFAULT NULL,
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    `base_tier` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_idx_option`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_option_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_option_idx')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_option_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_option_tmp')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_idx_option_aggregate`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_option_aggregate_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_option_aggregate_idx')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `option_id` INT(10) UNSIGNED DEFAULT '0',
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`, `option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_option_aggregate_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_option_aggregate_tmp')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `option_id` INT(10) UNSIGNED DEFAULT '0',
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`, `option_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_eav')}_idx`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_eav_indexer_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_eav_indexer_idx')}` (
    `entity_id` int(10) unsigned NOT NULL,
     `attribute_id` smallint(5) unsigned NOT NULL,
     `store_id` smallint(5) unsigned NOT NULL,
     `value` int(10) unsigned NOT NULL,
     PRIMARY KEY  (`entity_id`,`attribute_id`,`store_id`,`value`),
     KEY `IDX_ENTITY` (`entity_id`),
     KEY `IDX_ATTRIBUTE` (`attribute_id`),
     KEY `IDX_STORE` (`store_id`),
     KEY `IDX_VALUE` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_eav_indexer_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_eav_indexer_tmp')}` (
    `entity_id` int(10) unsigned NOT NULL,
     `attribute_id` smallint(5) unsigned NOT NULL,
     `store_id` smallint(5) unsigned NOT NULL,
     `value` int(10) unsigned NOT NULL,
     PRIMARY KEY  (`entity_id`,`attribute_id`,`store_id`,`value`),
     KEY `IDX_ENTITY` (`entity_id`),
     KEY `IDX_ATTRIBUTE` (`attribute_id`),
     KEY `IDX_STORE` (`store_id`),
     KEY `IDX_VALUE` (`value`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_eav_decimal')}_idx`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_eav_decimal_indexer_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_eav_decimal_indexer_idx')}` (
     `entity_id` int(10) unsigned NOT NULL,
     `attribute_id` smallint(5) unsigned NOT NULL,
     `store_id` smallint(5) unsigned NOT NULL,
     `value` decimal(12,4) NOT NULL,
     PRIMARY KEY  (`entity_id`,`attribute_id`,`store_id`,`value`),
     KEY `IDX_ENTITY` (`entity_id`),
     KEY `IDX_ATTRIBUTE` (`attribute_id`),
     KEY `IDX_STORE` (`store_id`),
     KEY `IDX_VALUE` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_eav_decimal_indexer_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_eav_decimal_indexer_tmp')}` (
     `entity_id` int(10) unsigned NOT NULL,
     `attribute_id` smallint(5) unsigned NOT NULL,
     `store_id` smallint(5) unsigned NOT NULL,
     `value` decimal(12,4) NOT NULL,
     PRIMARY KEY  (`entity_id`,`attribute_id`,`store_id`,`value`),
     KEY `IDX_ENTITY` (`entity_id`),
     KEY `IDX_ATTRIBUTE` (`attribute_id`),
     KEY `IDX_STORE` (`store_id`),
     KEY `IDX_VALUE` (`value`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_idx`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_idx')}` (
     `entity_id` int(10) unsigned NOT NULL,
     `customer_group_id` smallint(5) unsigned NOT NULL,
     `website_id` smallint(5) unsigned NOT NULL,
     `price` decimal(12,4) default NULL,
     `final_price` decimal(12,4) default NULL,
     `min_price` decimal(12,4) default NULL,
     `max_price` decimal(12,4) default NULL,
     `tier_price` decimal(12,4) default NULL,
     PRIMARY KEY  (`entity_id`,`customer_group_id`,`website_id`),
     KEY `IDX_CUSTOMER_GROUP` (`customer_group_id`),
     KEY `IDX_WEBSITE` (`website_id`),
     KEY `IDX_MIN_PRICE` (`min_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_tmp')}` (
     `entity_id` int(10) unsigned NOT NULL,
     `customer_group_id` smallint(5) unsigned NOT NULL,
     `website_id` smallint(5) unsigned NOT NULL,
     `price` decimal(12,4) default NULL,
     `final_price` decimal(12,4) default NULL,
     `min_price` decimal(12,4) default NULL,
     `max_price` decimal(12,4) default NULL,
     `tier_price` decimal(12,4) default NULL,
     PRIMARY KEY  (`entity_id`,`customer_group_id`,`website_id`),
     KEY `IDX_CUSTOMER_GROUP` (`customer_group_id`),
     KEY `IDX_WEBSITE` (`website_id`),
     KEY `IDX_MIN_PRICE` (`min_price`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_product_index')}_idx`;

CREATE TABLE `{$installer->getTable('catalog/category_product_indexer_idx')}` (
     `category_id` int(10) unsigned NOT NULL default '0',
     `product_id` int(10) unsigned NOT NULL default '0',
     `position` int(10) NOT NULL default '0',
     `is_parent` tinyint(1) unsigned NOT NULL default '0',
     `store_id` smallint(5) unsigned NOT NULL default '0',
     `visibility` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_product_indexer_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/category_product_indexer_tmp')}` (
     `category_id` int(10) unsigned NOT NULL default '0',
     `product_id` int(10) unsigned NOT NULL default '0',
     `position` int(10) NOT NULL default '0',
     `is_parent` tinyint(1) unsigned NOT NULL default '0',
     `store_id` smallint(5) unsigned NOT NULL default '0',
     `visibility` tinyint(3) unsigned NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('tmp_category_index_enabled_products')}`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_product_enabled_indexer_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/category_product_enabled_indexer_idx')}` (
    `product_id` int(10) unsigned NOT NULL DEFAULT '0',
    `visibility` int(11) unsigned NOT NULL DEFAULT '0',
    KEY `IDX_PRODUCT` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_product_enabled_indexer_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/category_product_enabled_indexer_tmp')}` (
    `product_id` int(10) unsigned NOT NULL DEFAULT '0',
    `visibility` int(11) unsigned NOT NULL DEFAULT '0',
    KEY `IDX_PRODUCT` (`product_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('tmp_category_index_anchor_categories')}`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_anchor_indexer_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/category_anchor_indexer_idx')}` (
    `category_id` int(10) unsigned NOT NULL DEFAULT '0',
    `path` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
    KEY `IDX_CATEGORY` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_anchor_indexer_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/category_anchor_indexer_tmp')}` (
    `category_id` int(10) unsigned NOT NULL DEFAULT '0',
    `path` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
    KEY `IDX_CATEGORY` (`category_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('tmp_category_index_anchor_products')}`;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_anchor_products_indexer_idx')}`;
CREATE TABLE `{$installer->getTable('catalog/category_anchor_products_indexer_idx')}` (
    `category_id` int(10) unsigned NOT NULL DEFAULT '0',
    `product_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/category_anchor_products_indexer_tmp')}`;
CREATE TABLE `{$installer->getTable('catalog/category_anchor_products_indexer_tmp')}` (
    `category_id` int(10) unsigned NOT NULL DEFAULT '0',
    `product_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_price_indexer_cfg_option_aggregate_tmp')}`;
    CREATE TABLE `{$installer->getTable('catalog/product_price_indexer_cfg_option_aggregate_tmp')}` (
        `parent_id` int(10) unsigned NOT NULL,
        `child_id` int(10) unsigned NOT NULL,
        `customer_group_id` smallint(5) unsigned NOT NULL,
        `website_id` smallint(5) unsigned NOT NULL,
        `price` decimal(12,4) default NULL,
        `tier_price` decimal(12,4) default NULL,
    PRIMARY KEY  (`parent_id`,`child_id`,`customer_group_id`,`website_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

");

}

$installer->endSetup();

$installer->installEntities();




// Create Root Catalog Node
Mage::getModel('catalog/category')
    ->setStoreId(0)
    ->setId(1)
    ->setPath(1)
    ->setName('Root Catalog')
    ->setInitialSetupFlag(true)
    ->save();

/* @var $category Mage_Catalog_Model_Category */
$category = Mage::getModel('catalog/category');

$category->setStoreId(0)
    #->setName('Default Category')
	->setName('所有分类')
    ->setDisplayMode('PRODUCTS')
    ->setAttributeSetId($category->getDefaultAttributeSetId())
    ->setIsActive(1)
    ->setPath('1')
    ->setInitialSetupFlag(true)
    ->save();

$installer->setConfigData('catalog/category/root_id', $category->getId());

$installer->addAttributeGroup('catalog_product', 'Default', 'Design', 6);

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

// update General Group
$installer->updateAttributeGroup($entityTypeId, $attributeSetId, $attributeGroupId, 'attribute_group_name', 'General Information');
$installer->updateAttributeGroup($entityTypeId, $attributeSetId, $attributeGroupId, 'sort_order', '10');

$groups = array(
    'display'   => array(
        'name'  => 'Display Settings',
        'sort'  => 20,
        'id'    => null
    ),
    'design'    => array(
        'name'  => 'Custom Design',
        'sort'  => 30,
        'id'    => null
    )
);

foreach ($groups as $k => $groupProp) {
    $installer->addAttributeGroup($entityTypeId, $attributeSetId, $groupProp['name'], $groupProp['sort']);
    $groups[$k]['id'] = $installer->getAttributeGroupId($entityTypeId, $attributeSetId, $groupProp['name']);
}

// update attributes group and sort
$attributes = array(
    'custom_design'         => array(
        'group' => 'design',
        'sort'  => 10
    ),
    'custom_design_apply'   => array(
        'group' => 'design',
        'sort'  => 20
    ),
    'custom_design_from'    => array(
        'group' => 'design',
        'sort'  => 30
    ),
    'custom_design_to'      => array(
        'group' => 'design',
        'sort'  => 40
    ),
    'page_layout'           => array(
        'group' => 'design',
        'sort'  => 50
    ),
    'custom_layout_update'  => array(
        'group' => 'design',
        'sort'  => 60
    ),
    'display_mode'          => array(
        'group' => 'display',
        'sort'  => 10
    ),
    'landing_page'          => array(
        'group' => 'display',
        'sort'  => 20
    ),
    'is_anchor'             => array(
        'group' => 'display',
        'sort'  => 30
    ),
    'available_sort_by'     => array(
        'group' => 'display',
        'sort'  => 40
    ),
    'default_sort_by'       => array(
        'group' => 'display',
        'sort'  => 50
    ),
);

foreach ($attributes as $attributeCode => $attributeProp) {
    $installer->addAttributeToGroup(
        $entityTypeId,
        $attributeSetId,
        $groups[$attributeProp['group']]['id'],
        $attributeCode,
        $attributeProp['sort']
    );
}

$describe = $installer->getConnection()->describeTable($installer->getTable('catalog/eav_attribute'));
foreach ($describe as $columnData) {
    if ($columnData['COLUMN_NAME'] == 'attribute_id') {
        continue;
    }
    $installer->getConnection()->dropColumn($installer->getTable('eav/attribute'), $columnData['COLUMN_NAME']);
}

$attributes = array(
    $installer->getAttributeId('catalog_product', 'cost')
);

$sql    = $installer->getConnection()->quoteInto("SELECT * FROM `{$installer->getTable('catalog/eav_attribute')}` WHERE attribute_id IN (?)", $attributes);
$data   = $installer->getConnection()->fetchAll($sql);

foreach ($data as $row) {
    $row['apply_to'] = array_flip(explode(',', $row['apply_to']));
    unset($row['apply_to']['configurable']);
    $row['apply_to'] = implode(',', array_flip($row['apply_to']));

    $installer->run("UPDATE `{$installer->getTable('catalog/eav_attribute')}`
                SET `apply_to` = '{$row['apply_to']}'
                WHERE `attribute_id` = {$row['attribute_id']}");
}

$installer->updateEntityType('catalog_category', 'entity_attribute_collection', 'catalog/category_attribute_collection');
$installer->updateEntityType('catalog_product', 'entity_attribute_collection', 'catalog/product_attribute_collection');

$installer->updateAttribute($installer->getEntityTypeId('catalog_category'), 'is_active', 'is_required', true);
$installer->updateAttribute('catalog_category', 'url_key', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
$installer->updateAttribute('catalog_category', 'url_path', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);

$installer->updateAttribute('catalog_product', 'url_key', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
$installer->updateAttribute('catalog_product', 'url_path', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);

$table = $this->getTable('catalog/category_product_index');

/**
 * Remove data duplicates
 */
$stmt = $installer->getConnection()->query(
    'SELECT * FROM ' . $table . ' GROUP BY category_id, product_id, store_id HAVING count(*)>1'
);

while ($row = $stmt->fetch()) {
    $condition = 'category_id=' . $row['category_id']
        . ' AND product_id=' . $row['product_id']
        . ' AND store_id=' . $row['store_id'] . ' AND is_parent=0';
    $installer->getConnection()->delete($table, $condition);
}

$installer->getConnection()->addKey(
    $table,
    'UNQ_CATEGORY_PRODUCT',
    array('category_id', 'product_id', 'store_id'),
    'unique'
);

$installer->updateAttribute('catalog_product', 'custom_layout_update', 'is_global',
    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);

$installer->updateAttribute('catalog_category', 'url_path', 'is_unique', 0);
$installer->updateAttribute('catalog_product', 'url_path', 'is_unique', 0);

$installer->getConnection()->dropKey($installer->getTable('catalog/product_option_price'),
    'IDX_CATALOG_PRODUCT_OPTION_PRICE_SI_OI');
$installer->getConnection()->addKey($installer->getTable('catalog/product_option_price'),
    'UNQ_OPTION_STORE', array('option_id', 'store_id'), 'unique');

$installer->getConnection()->dropKey($installer->getTable('catalog/product_option_title'),
    'IDX_CATALOG_PRODUCT_OPTION_TITLE_SI_OI');
$installer->getConnection()->addKey($installer->getTable('catalog/product_option_title'),
    'UNQ_OPTION_STORE', array('option_id', 'store_id'), 'unique');

$installer->getConnection()->dropKey($installer->getTable('catalog/product_option_type_price'),
    'IDX_CATALOG_PRODUCT_OPTION_TYPE_PRICE_SI_OTI');
$installer->getConnection()->addKey($installer->getTable('catalog/product_option_type_price'),
    'UNQ_OPTION_TYPE_STORE', array('option_type_id', 'store_id'), 'unique');

$installer->getConnection()->dropKey($installer->getTable('catalog/product_option_type_title'),
    'IDX_CATALOG_PRODUCT_OPTION_TYPE_TITLE_SI_OTI');
$installer->getConnection()->addKey($installer->getTable('catalog/product_option_type_title'),
    'UNQ_OPTION_TYPE_STORE', array('option_type_id', 'store_id'), 'unique');
$installer->getConnection()->addKey($installer->getTable('catalog/product_attribute_tier_price'),
    'UNQ_CATALOG_PRODUCT_TIER_PRICE', array('entity_id', 'all_groups', 'customer_group_id', 'qty', 'website_id'), 'unique');

$table = $this->getTable('catalog/category_product');

/**
 * Remove data duplicates
 */
$installer->getConnection()->changeColumn($table, 'position', 'position', "INT( 10 ) NOT NULL DEFAULT '0'");

$table = $this->getTable('catalog/category_product_index');

/**
 * Remove data duplicates
 */
$installer->getConnection()->changeColumn($table, 'position', 'position', "INT( 10 ) NOT NULL DEFAULT '0'");

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('catalog_category', 'include_in_menu',  array(
    'type'     => 'int',
    'label'    => 'Include in Navigation Menu',
    'input'    => 'select',
    'source'   => 'eav/entity_attribute_source_boolean',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required' => false,
    'default'  => 1
));

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'include_in_menu',
    '10'
);

$attributeId = $installer->getAttributeId($entityTypeId, 'include_in_menu');

$installer->run("
INSERT INTO `{$installer->getTable('catalog_category_entity_int')}`
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, '1'
        FROM `{$installer->getTable('catalog_category_entity')}`;
");

$installer->getConnection()->addKey($installer->getTable('catalog/product_link'),
    'IDX_UNIQUE', array('link_type_id', 'product_id', 'linked_product_id'), 'unique');

$installer->run("UPDATE {$installer->getTable('catalog/eav_attribute')}
    SET is_used_for_promo_rules = is_used_for_price_rules"
);

$productEntityTypeId = $installer->getEntityTypeId('catalog_product');

$installer->updateAttribute($productEntityTypeId, 'minimal_price', 'is_required', 0);
$installer->updateAttribute($productEntityTypeId, 'required_options', 'is_required', 0);
$installer->updateAttribute($productEntityTypeId, 'has_options', 'is_required', 0);

$entityTypeId = $installer->getEntityTypeId('catalog_category');
$designApplyAttributeId = $installer->getAttributeId($entityTypeId, 'custom_design_apply');
$designAttributeId = $installer->getAttributeId($entityTypeId, 'custom_design');
$catalogCategoryEntityIntTable = $installer->getAttributeTable($entityTypeId, $designApplyAttributeId);
$eavAttributeTable = $installer->getTable('eav/attribute');

$installer->addAttribute($entityTypeId, 'custom_use_parent_settings', array(
    'type'          => 'int',
    'input'         => 'select',
    'label'         => 'Use Parent Category Settings',
    'source'        => 'eav/entity_attribute_source_boolean',
    'required'      => 0,
    'group'         => 'Custom Design',
    'sort_order'    => '5',
    'global'        => 0
));
$installer->addAttribute($entityTypeId, 'custom_apply_to_products', array(
    'type'          => 'int',
    'input'         => 'select',
    'label'         => 'Apply To Products',
    'source'        => 'eav/entity_attribute_source_boolean',
    'required'      => 0,
    'group'         => 'Custom Design',
    'sort_order'    => '6',
    'global'        => 0
));
$useParentSettingsAttributeId = $installer->getAttributeId($entityTypeId, 'custom_use_parent_settings');
$applyToProductsAttributeId = $installer->getAttributeId($entityTypeId, 'custom_apply_to_products');



$attributeIdExpr = new Zend_Db_Expr(
    'IF (e_a.attribute_id = e.attribute_id,'.
    $useParentSettingsAttributeId.', '.
    $applyToProductsAttributeId .')');
$productValueExpr = new Zend_Db_Expr('IF (e.value IN (1,3), 1, 0)');
$valueExpr = new Zend_Db_Expr('IF (e_a.attribute_id = e.attribute_id, 1, '. $productValueExpr .')');
$select = $installer->getConnection()->select()
    ->from(
        array('e' => $catalogCategoryEntityIntTable),
        array(
            'entity_type_id',
            'attribute_id' => $attributeIdExpr,
            'store_id',
            'entity_id',
            'value' => $valueExpr
        )
    )
    ->joinCross(
        array('e_a' => $eavAttributeTable),
        array())
    ->where('e_a.attribute_id IN (?)', array($designApplyAttributeId, $designAttributeId))
    ->where('e.attribute_id = ?', $designApplyAttributeId)
    ->order(array('e.entity_id', 'attribute_id'));

$insertArray = array(
    'entity_type_id',
    'attribute_id',
    'store_id',
    'entity_id',
    'value'
);

$sqlQuery = $select->insertFromSelect($catalogCategoryEntityIntTable, $insertArray, false);
$installer->getConnection()->query($sqlQuery);

$installer->addAttribute('catalog_category', 'filter_price_range', array(
    'group'         => 'Display Settings',
    'type'          => 'int',
    'label'         => 'Layered Navigation Price Step',
    'required'      => false,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'       => 1,
    'input_renderer'=> 'adminhtml/catalog_category_helper_pricestep',
));

$connection = $installer->getConnection();

$indexTable = $installer->getTable('catalog/category_product_index');
$connection->modifyColumn($indexTable, 'position', 'int(10) unsigned NULL default NULL');

$tmpTable = $installer->getTable('catalog/category_anchor_products_indexer_idx');
$connection->addColumn($tmpTable, 'position', 'int(10) unsigned NULL default NULL');

$categoryEntityTypeId = $installer->getEntityTypeId('catalog_category');
$productEntityTypeId = $installer->getEntityTypeId('catalog_product');
$installer->updateAttribute($categoryEntityTypeId, 'description', 'is_wysiwyg_enabled', 1);
$installer->updateAttribute($categoryEntityTypeId, 'description', 'is_html_allowed_on_front', 1);
$installer->updateAttribute($productEntityTypeId, 'description', 'is_wysiwyg_enabled', 1);
$installer->updateAttribute($productEntityTypeId, 'description', 'is_html_allowed_on_front', 1);
$installer->updateAttribute($productEntityTypeId, 'short_description', 'is_wysiwyg_enabled', 1);
$installer->updateAttribute($productEntityTypeId, 'short_description', 'is_html_allowed_on_front', 1);

$installer->removeAttribute('catalog_category', 'custom_design_apply');

// the fix for a typo that was in mysql4-upgrade-0.7.71-0.7.72 line 32
$installer->removeAttribute('catalog_product', 'category_ids');

$entityTypeId = $installer->getEntityTypeId('catalog_product');
$installer->run("
    UPDATE `{$installer->getTable('eav/attribute')}`
    SET `source_model` = 'eav/entity_attribute_source_boolean'
    WHERE `entity_type_id` = {$entityTypeId} AND `frontend_input` = 'boolean'"
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog/product_super_attribute_label'),
    'FK_CATALOG_PRODUCT_SUPER_ATTRIBUTE_LABEL_ATTRIBUTE'
);
$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog/product_super_attribute_label'),
    'FK_CATALOG_PRODUCT_SUPER_ATTRIBUTE_LABEL_STORE'
);
$installer->getConnection()->addConstraint('FK_CATALOG_PROD_SUPER_ATTR_LABEL_ATTR',
    $installer->getTable('catalog/product_super_attribute_label'), 'product_super_attribute_id',
    $installer->getTable('catalog/product_super_attribute'), 'product_super_attribute_id'
);
$installer->getConnection()->addConstraint('FK_CATALOG_PROD_SUPER_ATTR_LABEL_STORE',
    $installer->getTable('catalog/product_super_attribute_label'), 'store_id',
    $installer->getTable('core/store'), 'store_id'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog_product_entity_datetime'),
    'FK_CATALOG_PRODUCT_ENTITY_DATETIME_PRODUCT_ENTITY'
);
$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog_product_entity_decimal'),
    'FK_CATALOG_PRODUCT_ENTITY_DECIMAL_PRODUCT_ENTITY'
);
$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog_product_entity_varchar'),
    'FK_CATALOG_PRODUCT_ENTITY_VARCHAR_PRODUCT_ENTITY'
);
$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog_product_entity_tier_price'),
    'FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_PRODUCT_ENTITY'
);
$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog_category_product_index'),
    'FK_CATALOG_CATEGORY_PRODUCT_INDEX_CATEGORY_ENTITY'
);
$installer->getConnection()->dropForeignKey(
    $installer->getTable('catalog_category_product_index'),
    'FK_CATALOG_CATEGORY_PRODUCT_INDEX_PRODUCT_ENTITY'
);

$installer->getConnection()->addConstraint('FK_CATALOG_PROD_ENTITY_DATETIME_PROD_ENTITY',
    $installer->getTable('catalog_product_entity_datetime'), 'entity_id',
    $installer->getTable('catalog_product_entity'), 'entity_id'
);
$installer->getConnection()->addConstraint('FK_CATALOG_PROD_ENTITY_DECIMAL_PROD_ENTITY',
    $installer->getTable('catalog_product_entity_decimal'), 'entity_id',
    $installer->getTable('catalog_product_entity'), 'entity_id'
);
$installer->getConnection()->addConstraint('FK_CATALOG_PROD_ENTITY_VARCHAR_PROD_ENTITY',
    $installer->getTable('catalog_product_entity_varchar'), 'entity_id',
    $installer->getTable('catalog_product_entity'), 'entity_id'
);
$installer->getConnection()->addConstraint('FK_CATALOG_PROD_ENTITY_TIER_PRICE_PROD_ENTITY',
    $installer->getTable('catalog_product_entity_tier_price'), 'entity_id',
    $installer->getTable('catalog_product_entity'), 'entity_id'
);
$installer->getConnection()->addConstraint('FK_CATALOG_CATEGORY_PROD_IDX_CATEGORY_ENTITY',
    $installer->getTable('catalog_category_product_index'), 'category_id',
    $installer->getTable('catalog_category_entity'), 'entity_id'
);
$installer->getConnection()->addConstraint('FK_CATALOG_CATEGORY_PROD_IDX_PROD_ENTITY',
    $installer->getTable('catalog_category_product_index'), 'product_id',
    $installer->getTable('catalog_product_entity'), 'entity_id'
);

$productEntityTypeId = $installer->getEntityTypeId('catalog_category');
$installer->updateAttribute($productEntityTypeId, 'include_in_menu', 'is_required', true);

$entityTypeId = $installer->getEntityTypeId('catalog_product');
$installer->updateAttribute($entityTypeId, 'custom_layout_update', 'backend_model', 'catalog/attribute_backend_customlayoutupdate');

$entityTypeId = $installer->getEntityTypeId('catalog_category');
$installer->updateAttribute($entityTypeId, 'custom_layout_update', 'backend_model', 'catalog/attribute_backend_customlayoutupdate');

$entityTypeId = $installer->getEntityTypeId('catalog_product');
$installer->updateAttribute($entityTypeId, 'meta_description', 'frontend_class', 'validate-length maximum-length-255');

$installer->run("
    ALTER TABLE {$installer->getTable('catalog/product_super_attribute_pricing')}
    CHANGE `pricing_value` `pricing_value` DECIMAL(20,4) NULL"
);

$connection = $installer->getConnection();
$table      = $installer->getTable('catalog/product_super_attribute_pricing');
$connection->changeColumn($table, 'pricing_value', 'pricing_value', 'DECIMAL(12, 4) NULL DEFAULT NULL');

$installer->addAttribute('catalog_category', 'thumbnail', array(
    'type'              => 'varchar',
    'backend'           => 'catalog/category_attribute_backend_image',
    'frontend'          => '',
    'label'             => 'Thumbnail Image',
    'input'             => 'image',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
));

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'thumbnail',
    '4'
);
