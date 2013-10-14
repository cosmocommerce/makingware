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
 * @package     Mage_CatalogInventory
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS `{$this->getTable('cataloginventory_stock')}`;
CREATE TABLE `{$this->getTable('cataloginventory_stock')}` (
  `stock_id` smallint(4) unsigned NOT NULL auto_increment,
  `stock_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalog inventory Stocks list';

insert into `{$this->getTable('cataloginventory_stock')}`(`stock_id`,`stock_name`) values (1, 'Default');

-- DROP TABLE IF EXISTS `{$this->getTable('cataloginventory_stock_item')}`;
CREATE TABLE `{$this->getTable('cataloginventory_stock_item')}` (
    `item_id` int(10) unsigned NOT NULL auto_increment,
    `product_id` int(10) unsigned NOT NULL default '0',
    `stock_id` smallint(4) unsigned NOT NULL default '0',
    `qty` decimal(12,4) NOT NULL default '0.0000',
    `min_qty` decimal(12,4) NOT NULL default '0.0000',
    `use_config_min_qty` tinyint(1) unsigned NOT NULL default '1',
    `is_qty_decimal` tinyint(1) unsigned NOT NULL default '0',
    `backorders` tinyint(3) unsigned NOT NULL default '0',
    `use_config_backorders` tinyint(1) unsigned NOT NULL default '1',
    `min_sale_qty` decimal(12,4) NOT NULL default '1.0000',
    `use_config_min_sale_qty` tinyint(1) unsigned NOT NULL default '1',
    `max_sale_qty` decimal(12,4) NOT NULL default '0.0000',
    `use_config_max_sale_qty` tinyint(1) unsigned NOT NULL default '1',
    `is_in_stock` tinyint(1) unsigned NOT NULL default '0',
    PRIMARY KEY  (`item_id`),
    UNIQUE KEY `IDX_STOCK_PRODUCT` (`product_id`,`stock_id`),
    KEY `FK_CATALOGINVENTORY_STOCK_ITEM_PRODUCT` (`product_id`),
    KEY `FK_CATALOGINVENTORY_STOCK_ITEM_STOCK` (`stock_id`),
    CONSTRAINT `FK_CATALOGINVENTORY_STOCK_ITEM_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$this->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_CATALOGINVENTORY_STOCK_ITEM_STOCK` FOREIGN KEY (`stock_id`) REFERENCES `{$this->getTable('cataloginventory_stock')}` (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Inventory Stock Item Data';

DROP TABLE IF EXISTS `{$installer->getTable('cataloginventory/stock_status')}_idx`;

CREATE TABLE `{$installer->getTable('cataloginventory/stock_status_indexer_idx')}` (
     `product_id` int(10) unsigned NOT NULL,
     `website_id` smallint(5) unsigned NOT NULL,
     `stock_id` smallint(4) unsigned NOT NULL,
     `qty` decimal(12,4) NOT NULL default '0.0000',
     `stock_status` tinyint(3) unsigned NOT NULL,
     PRIMARY KEY  (`product_id`,`website_id`,`stock_id`),
     KEY `FK_CATALOGINVENTORY_STOCK_STATUS_STOCK` (`stock_id`),
     KEY `FK_CATALOGINVENTORY_STOCK_STATUS_WEBSITE` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('cataloginventory/stock_status_indexer_tmp')}` (
     `product_id` int(10) unsigned NOT NULL,
     `website_id` smallint(5) unsigned NOT NULL,
     `stock_id` smallint(4) unsigned NOT NULL,
     `qty` decimal(12,4) NOT NULL default '0.0000',
     `stock_status` tinyint(3) unsigned NOT NULL,
     PRIMARY KEY  (`product_id`,`website_id`,`stock_id`),
     KEY `FK_CATALOGINVENTORY_STOCK_STATUS_STOCK` (`stock_id`),
     KEY `FK_CATALOGINVENTORY_STOCK_STATUS_WEBSITE` (`website_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

");

$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'low_stock_date', 'datetime');
$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'notify_stock_qty', 'decimal(12,4)');
$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'use_config_notify_stock_qty', "tinyint(1) unsigned NOT NULL default '1'");
$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'manage_stock', 'tinyint(1) unsigned NOT NULL DEFAULT 0');
$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'use_config_manage_stock', 'tinyint(1) unsigned NOT NULL DEFAULT 1');
$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'stock_status_changed_automatically', 'tinyint(1) unsigned NOT NULL DEFAULT 0');

foreach (array(
    'cataloginventory/options/min_qty'          => 'cataloginventory/item_options/min_qty',
    'cataloginventory/options/min_sale_qty'     => 'cataloginventory/item_options/min_sale_qty',
    'cataloginventory/options/max_sale_qty'     => 'cataloginventory/item_options/max_sale_qty',
    'cataloginventory/options/backorders'       => 'cataloginventory/item_options/backorders',
    'cataloginventory/options/notify_stock_qty' => 'cataloginventory/item_options/notify_stock_qty',
    'cataloginventory/options/manage_stock'     => 'cataloginventory/item_options/manage_stock',
    ) as $was => $become) {
    $installer->run(sprintf("UPDATE `%s` SET `path` = '%s' WHERE `path` = '%s'",
        $this->getTable('core/config_data'), $become, $was
    ));
}

$connection = $installer->getConnection();
/* @var $connection Varien_Db_Adapter_Pdo_Mysql */
$connection->beginTransaction();
try {
    $installer->run("
    CREATE TABLE `{$installer->getTable('cataloginventory_stock_status')}` (
      `product_id` int(10) unsigned NOT NULL,
      `website_id` smallint(5) unsigned NOT NULL,
      `stock_id` smallint(4) unsigned NOT NULL,
      `qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
      `stock_status` tinyint(3) unsigned NOT NULL,
      PRIMARY KEY (`product_id`,`website_id`,`stock_id`),
      CONSTRAINT `FK_CATALOGINVENTORY_STOCK_STATUS_STOCK` FOREIGN KEY (`stock_id`) REFERENCES `{$installer->getTable('cataloginventory_stock')}` (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `FK_CATALOGINVENTORY_STOCK_STATUS_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `FK_CATALOGINVENTORY_STOCK_STATUS_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

    Mage::getModel('cataloginventory/stock_status')->rebuild();
}
catch (Exception $e) {
    $connection->rollBack();
    throw $e;
}
$connection->commit();

/* @var $installer Mage_Core_Model_Resource_Setup */

$tableCataloginventoryStockItem = $installer->getTable('cataloginventory_stock_item');

$connection = $installer->getConnection();
/* @var $connection Varien_Db_Adapter_Pdo_Mysql */

$connection->addColumn($tableCataloginventoryStockItem, 'use_config_qty_increments', "tinyint(1) unsigned NOT NULL default '1'");
$connection->addColumn($tableCataloginventoryStockItem, 'qty_increments', "decimal(12,4) NOT NULL DEFAULT '0.0000'");
/* @var $installer Mage_Core_Model_Resource_Setup */

$tableCataloginventoryStockItem = $installer->getTable('cataloginventory/stock_item');

$connection = $installer->getConnection();
/* @var $connection Varien_Db_Adapter_Pdo_Mysql */

$connection->addColumn($tableCataloginventoryStockItem, 'use_config_enable_qty_increments', "tinyint(1) unsigned NOT NULL default '1'");
$connection->addColumn($tableCataloginventoryStockItem, 'enable_qty_increments', "tinyint(1) unsigned NOT NULL default '0'");

$installer->endSetup();

