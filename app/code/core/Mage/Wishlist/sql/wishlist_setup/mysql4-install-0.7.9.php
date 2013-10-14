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
 * @package     Mage_Wishlist
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('wishlist')};
CREATE TABLE {$this->getTable('wishlist')} (
  `wishlist_id` int(10) unsigned NOT NULL auto_increment,
  `customer_id` int(10) unsigned NOT NULL default '0',
  `shared` tinyint(1) unsigned default '0',
  `sharing_code` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`wishlist_id`),
  UNIQUE KEY `FK_CUSTOMER` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Wishlist main';

-- DROP TABLE IF EXISTS {$this->getTable('wishlist_item')};
CREATE TABLE {$this->getTable('wishlist_item')} (
  `wishlist_item_id` int(10) unsigned NOT NULL auto_increment,
  `wishlist_id` int(10) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `store_id` int(10) unsigned NOT NULL default '0',
  `added_at` datetime default NULL,
  `description` text,
  PRIMARY KEY  (`wishlist_item_id`),
  KEY `FK_ITEM_WISHLIST` (`wishlist_id`),
  KEY `FK_WISHLIST_PRODUCT` (`product_id`),
  KEY `FK_WISHLIST_STORE` (`store_id`),
  CONSTRAINT `FK_ITEM_WISHLIST` FOREIGN KEY (`wishlist_id`) REFERENCES {$this->getTable('wishlist')} (`wishlist_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Wishlist items';

CREATE TABLE `{$this->getTable('wishlist/item_option')}` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wishlist_item_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Additional options for wishlist item';

    ");

$installer->run("

alter table {$this->getTable('wishlist_item')} add constraint `FK_WISHLIST_PRODUCT` foreign key (`product_id`) references {$this->getTable('catalog_product_entity')} (`entity_id`) on delete cascade  on update cascade
;

");

$installer->run("
ALTER TABLE {$this->getTable('wishlist_item')}
    CHANGE `store_id` `store_id` smallint(5) unsigned NOT NULL;
ALTER TABLE {$this->getTable('wishlist_item')}
    ADD CONSTRAINT `FK_WISHLIST_ITEM_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;
");

$tableWishlist  = $this->getTable('wishlist');
$tableCustomers = $this->getTable('customer/entity');

$installer->run("DELETE FROM {$tableWishlist} WHERE customer_id NOT IN (SELECT entity_id FROM {$tableCustomers})");

$installer->run("
ALTER TABLE {$tableWishlist}
    ADD CONSTRAINT `FK_CUSTOMER` FOREIGN KEY (`customer_id`)
    REFERENCES {$tableCustomers} (`entity_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;
");

$installer->getConnection()->dropForeignKey($installer->getTable('wishlist/item'), 'FK_WISHLIST_ITEM_STORE');
$installer->getConnection()->dropForeignKey($installer->getTable('wishlist/item'), 'FK_ITEM_WISHLIST');
$installer->getConnection()->dropForeignKey($installer->getTable('wishlist/item'), 'FK_WISHLIST_PRODUCT');
$installer->getConnection()->dropForeignKey($installer->getTable('wishlist/wishlist'), 'FK_CUSTOMER');

$installer->getConnection()->dropKey($installer->getTable('wishlist/item'), 'FK_ITEM_WISHLIST');
$installer->getConnection()->dropKey($installer->getTable('wishlist/item'), 'FK_WISHLIST_PRODUCT');
$installer->getConnection()->dropKey($installer->getTable('wishlist/item'), 'FK_WISHLIST_STORE');
$installer->getConnection()->dropKey($installer->getTable('wishlist/wishlist'), 'FK_CUSTOMER');

$installer->getConnection()->modifyColumn($installer->getTable('wishlist/item'), 'store_id',
    'smallint UNSIGNED DEFAULT NULL');

$installer->getConnection()->addKey($installer->getTable('wishlist/item'), 'IDX_WISHLIST', 'wishlist_id');
$installer->getConnection()->addKey($installer->getTable('wishlist/item'), 'IDX_PRODUCT', 'product_id');
$installer->getConnection()->addKey($installer->getTable('wishlist/item'), 'IDX_STORE', 'store_id');
$installer->getConnection()->addKey($installer->getTable('wishlist/wishlist'), 'UNQ_CUSTOMER', 'customer_id', 'unique');
$installer->getConnection()->addKey($installer->getTable('wishlist/wishlist'), 'IDX_IS_SHARED', 'shared');

$installer->getConnection()->addConstraint('FK_WISHLIST_ITEM_STORE',
    $installer->getTable('wishlist/item'), 'store_id',
    $installer->getTable('core/store'), 'store_id',
    'set null', 'cascade'
);
$installer->getConnection()->addConstraint('FK_WISHLIST_ITEM_WISHLIST',
    $installer->getTable('wishlist/item'), 'wishlist_id',
    $installer->getTable('wishlist/wishlist'), 'wishlist_id',
    'cascade', 'cascade'
);
$installer->getConnection()->addConstraint('FK_WISHLIST_ITEM_PRODUCT',
    $installer->getTable('wishlist/item'), 'product_id',
    $installer->getTable('catalog/product'), 'entity_id',
    'cascade', 'cascade'
);
$installer->getConnection()->addConstraint('FK_WISHLIST_CUSTOMER',
    $installer->getTable('wishlist/wishlist'), 'customer_id',
    $installer->getTable('customer/entity'), 'entity_id',
    'cascade', 'cascade'
);

$installer->getConnection()->addColumn($this->getTable('wishlist'), 'updated_at', 'datetime NULL DEFAULT NULL');
$installer->getConnection()->addColumn($this->getTable('wishlist/item'), 'qty', 'DECIMAL( 12, 4 ) NOT NULL');
$installer->getConnection()->addConstraint(
    'FK_WISHLIST_ITEM_OPTION_ITEM_ID',
    $this->getTable('wishlist/item_option'),
    'wishlist_item_id',
    $this->getTable('wishlist/item'),
    'wishlist_item_id'
);
$installer->endSetup();
