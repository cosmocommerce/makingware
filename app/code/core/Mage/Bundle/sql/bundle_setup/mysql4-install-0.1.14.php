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
 * @package     Mage_Bundle
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('catalog_product_bundle_option')};
CREATE TABLE {$this->getTable('catalog_product_bundle_option')} (
  `option_id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL,
  `required` tinyint(1) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  `type` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bundle Options';

DROP TABLE IF EXISTS {$this->getTable('catalog_product_bundle_option_link')};
DROP TABLE IF EXISTS {$this->getTable('catalog_product_bundle_selection')};
CREATE TABLE {$this->getTable('catalog_product_bundle_selection')} (
  `selection_id` int(10) unsigned NOT NULL auto_increment,
  `option_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL default '0',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `selection_price_type` tinyint(1) unsigned NOT NULL default '0',
  `selection_price_value` decimal(12,4) NOT NULL default '0.0000',
  `selection_qty` decimal(12,4) NOT NULL default '0.0000',
  `selection_can_change_qty` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`selection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bundle Selections';

DROP TABLE IF EXISTS {$this->getTable('catalog_product_bundle_option_value')};
CREATE TABLE {$this->getTable('catalog_product_bundle_option_value')} (
  `value_id` int(10) unsigned NOT NULL auto_increment,
  `option_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bundle Selections';

ALTER TABLE {$this->getTable('catalog_product_bundle_option')}
    ADD CONSTRAINT `FK_CATALOG_PRODUCT_BUNDLE_OPTION_PARENT` FOREIGN KEY (`parent_id`)
    REFERENCES {$this->getTable('catalog_product_entity')} (`entity_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE;
ALTER TABLE {$this->getTable('catalog_product_bundle_option_value')}
    ADD CONSTRAINT `FK_CATALOG_PRODUCT_BUNDLE_OPTION_VALUE_OPTION` FOREIGN KEY (`option_id`)
    REFERENCES {$this->getTable('catalog_product_bundle_option')} (`option_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE;
ALTER TABLE {$this->getTable('catalog_product_bundle_selection')}
    ADD CONSTRAINT `FK_CATALOG_PRODUCT_BUNDLE_SELECTION_OPTION` FOREIGN KEY (`option_id`)
    REFERENCES {$this->getTable('catalog_product_bundle_option')} (`option_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    ADD CONSTRAINT `FK_CATALOG_PRODUCT_BUNDLE_SELECTION_PRODUCT` FOREIGN KEY (`product_id`)
    REFERENCES {$this->getTable('catalog_product_entity')} (`entity_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE;

ALTER TABLE {$this->getTable('catalog_product_bundle_selection')}
    ADD COLUMN parent_product_id INT(10) UNSIGNED NOT NULL AFTER option_id;

UPDATE `{$installer->getTable('catalog/product')}` SET `has_options` = '1'
    WHERE (entity_id IN (
        SELECT parent_product_id FROM `{$installer->getTable('bundle/selection')}` GROUP BY parent_product_id
    ));

CREATE TABLE `{$installer->getTable('bundle/price_index')}` (
  `entity_id` int(10) unsigned NOT NULL,
  `website_id` smallint(5) unsigned NOT NULL,
  `customer_group_id` smallint(3) unsigned NOT NULL,
  `min_price` decimal(12,4) NOT NULL,
  `max_price` decimal(12,4) NOT NULL,
  PRIMARY KEY  (`entity_id`,`website_id`,`customer_group_id`),
  KEY `IDX_WEBSITE` (`website_id`),
  KEY `IDX_CUSTOMER_GROUP` (`customer_group_id`),
  CONSTRAINT `CATALOG_PRODUCT_BUNDLE_PRICE_INDEX_CUSTOMER_GROUP` FOREIGN KEY (`customer_group_id`) REFERENCES `{$installer->getTable('customer/customer_group')}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCT_BUNDLE_PRICE_INDEX_PRODUCT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCT_BUNDLE_PRICE_INDEX_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core/website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('bundle/stock_index')}` (
  `entity_id` INT(10) UNSIGNED NOT NULL,
  `website_id` SMALLINT(5) UNSIGNED NOT NULL,
  `stock_id` SMALLINT(5) UNSIGNED NOT NULL,
  `option_id` INT(10) UNSIGNED DEFAULT 0,
  `stock_status` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`entity_id`,`stock_id`,`website_id`,`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_bundle`;

CREATE TABLE `{$installer->getTable('bundle/price_indexer_idx')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `price_type` TINYINT(1) UNSIGNED NOT NULL,
    `special_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_percent` DECIMAL(12,4) DEFAULT NULL,
    `orig_price` DECIMAL(12,4) DEFAULT NULL,
    `price` DECIMAL(12,4) DEFAULT NULL,
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    `base_tier` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('bundle/price_indexer_tmp')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `price_type` TINYINT(1) UNSIGNED NOT NULL,
    `special_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_percent` DECIMAL(12,4) DEFAULT NULL,
    `orig_price` DECIMAL(12,4) DEFAULT NULL,
    `price` DECIMAL(12,4) DEFAULT NULL,
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    `base_tier` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_bndl_sel`;

CREATE TABLE `{$installer->getTable('bundle/selection_indexer_idx')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `option_id` INT(10) UNSIGNED DEFAULT '0',
    `selection_id` INT(10) UNSIGNED DEFAULT '0',
    `group_type` TINYINT(1) UNSIGNED DEFAULT '0',
    `is_required` TINYINT(1) UNSIGNED DEFAULT '0',
    `price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`, `option_id`, `selection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('bundle/selection_indexer_tmp')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `option_id` INT(10) UNSIGNED DEFAULT '0',
    `selection_id` INT(10) UNSIGNED DEFAULT '0',
    `group_type` TINYINT(1) UNSIGNED DEFAULT '0',
    `is_required` TINYINT(1) UNSIGNED DEFAULT '0',
    `price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`, `option_id`, `selection_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('catalog/product_index_price')}_bndl_opt`;

CREATE TABLE `{$installer->getTable('bundle/option_indexer_idx')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `option_id` INT(10) UNSIGNED DEFAULT '0',
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `alt_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    `alt_tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`, `option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('bundle/option_indexer_tmp')}` (
    `entity_id` INT(10) UNSIGNED NOT NULL,
    `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL,
    `website_id` SMALLINT(5) UNSIGNED NOT NULL,
    `option_id` INT(10) UNSIGNED DEFAULT '0',
    `min_price` DECIMAL(12,4) DEFAULT NULL,
    `alt_price` DECIMAL(12,4) DEFAULT NULL,
    `max_price` DECIMAL(12,4) DEFAULT NULL,
    `tier_price` DECIMAL(12,4) DEFAULT NULL,
    `alt_tier_price` DECIMAL(12,4) DEFAULT NULL,
    PRIMARY KEY (`entity_id`,`customer_group_id`,`website_id`, `option_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('bundle/selection_price')} (
    `selection_id` int(10) unsigned NOT NULL,
    `website_id` smallint(5) unsigned NOT NULL,
    `selection_price_type` tinyint(1) unsigned NOT NULL default '0',
    `selection_price_value` decimal(12,4) NOT NULL default '0.0000',
    PRIMARY KEY  (`selection_id`, `website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->addAttribute('catalog_product', 'price_type', array(
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => '',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => false,
        'required'          => true,
        'user_defined'      => false,
        'default'           => '',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'bundle',
        'is_configurable'   => false
    ));

$installer->addAttribute('catalog_product', 'sku_type', array(
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => '',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => false,
        'required'          => true,
        'user_defined'      => false,
        'default'           => '',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'bundle',
        'is_configurable'   => false
    ));

$installer->addAttribute('catalog_product', 'weight_type', array(
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => '',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => false,
        'required'          => true,
        'user_defined'      => false,
        'default'           => '',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'bundle',
        'is_configurable'   => false
    ));

$installer->addAttribute('catalog_product', 'price_view', array(
        'group'             => 'Prices',
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Price View',
        'input'             => 'select',
        'class'             => '',
        'source'            => 'bundle/product_attribute_source_price_view',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => true,
        'user_defined'      => false,
        'default'           => '',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'bundle',
        'is_configurable'   => false
    ));

$fieldList = array('price','special_price','special_from_date','special_to_date',
    'minimal_price','cost','tier_price','weight');
foreach ($fieldList as $field) {
    $applyTo = explode(',', $installer->getAttribute('catalog_product', $field, 'apply_to'));
    if (!in_array('bundle', $applyTo)) {
        $applyTo[] = 'bundle';
        $installer->updateAttribute('catalog_product', $field, 'apply_to', join(',', $applyTo));
    }
}

$installer->addAttribute('catalog_product', 'shipment_type', array(
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Shipment',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => false,
        'required'          => true,
        'user_defined'      => false,
        'default'           => '',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'bundle',
        'is_configurable'   => false
    ));

$installer->updateAttribute('catalog_product', 'price_type', 'used_in_product_listing', 1);
$installer->updateAttribute('catalog_product', 'price_view', 'used_in_product_listing', 1);
$installer->updateAttribute('catalog_product', 'shipment_type', 'used_in_product_listing', 1);
$installer->updateAttribute('catalog_product', 'weight_type', 'used_in_product_listing', 1);
$installer->getConnection()->addKey($installer->getTable('bundle/option_value'), 'UNQ_OPTION_STORE',
    array('option_id', 'store_id'), 'unique');
$attributes = array(
    $installer->getAttributeId('catalog_product', 'cost')
);

$sql    = $installer->getConnection()->quoteInto("SELECT * FROM `{$installer->getTable('catalog/eav_attribute')}` WHERE attribute_id IN (?)", $attributes);
$data   = $installer->getConnection()->fetchAll($sql);

foreach ($data as $row) {
    $row['apply_to'] = array_flip(explode(',', $row['apply_to']));
    unset($row['apply_to']['bundle']);
    $row['apply_to'] = implode(',', array_flip($row['apply_to']));

    $installer->run("UPDATE `{$installer->getTable('catalog/eav_attribute')}`
                SET `apply_to` = '{$row['apply_to']}'
                WHERE `attribute_id` = {$row['attribute_id']}");
}

$installer->run("
INSERT IGNORE INTO `{$installer->getTable('catalog/product_relation')}`
SELECT
  `parent_product_id`,
  `product_id`
FROM `{$installer->getTable('bundle/selection')}`;
");

$installer->getConnection()->addConstraint(
    'FK_BUNDLE_PRICE_SELECTION_ID',
    $this->getTable('bundle/selection_price'),
    'selection_id',
    $this->getTable('bundle/selection'),
    'selection_id'
);

$installer->getConnection()->addConstraint(
    'FK_BUNDLE_PRICE_SELECTION_WEBSITE',
    $this->getTable('bundle/selection_price'),
    'website_id',
    $this->getTable('core_website'),
    'website_id'
);
$installer->endSetup();
