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
 * @package     Mage_Reports
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Report events SQL
 *
 * @category   Mage
 * @package    Mage_Reports
 * @author      Magento Core Team <core@magentocommerce.com>
 */

$installer = $this;
/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('report_event_types')};
CREATE TABLE {$this->getTable('report_event_types')} (
  `event_type_id` smallint(6) unsigned NOT NULL auto_increment,
  `event_name` varchar(32) NOT NULL,
  PRIMARY KEY  (`event_type_id`),
  KEY `event_type_id` (`event_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('report_event_types')} VALUES
(1, 'catalog_product_view'),
(2, 'sendfriend_product'),
(3, 'catalog_product_compare_add_product'),
(4, 'checkout_cart_add_product'),
(5, 'wishlist_add_product'),
(6, 'wishlist_share');

-- DROP TABLE IF EXISTS {$this->getTable('report_event')};
CREATE TABLE {$this->getTable('report_event')} (
  `event_id` bigint(20) unsigned NOT NULL auto_increment,
  `logged_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `event_type_id` smallint(6) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `subject_id` int(10) unsigned NOT NULL default '0',
  `store_id` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`event_id`),
  KEY `subject_id` (`subject_id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('report_event_types')};
CREATE TABLE {$this->getTable('report_event_types')} (
  `event_type_id` smallint(6) unsigned NOT NULL auto_increment,
  `event_name` varchar(32) NOT NULL,
  PRIMARY KEY  (`event_type_id`),
  KEY `event_type_id` (`event_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('report_event_types')} VALUES
(1, 'catalog_product_view'),
(2, 'sendfriend_product'),
(3, 'catalog_product_compare_add_product'),
(4, 'checkout_cart_add_product'),
(5, 'wishlist_add_product'),
(6, 'wishlist_share');

DROP TABLE IF EXISTS {$this->getTable('report_event')};
CREATE TABLE {$this->getTable('report_event')} (
  `event_id` bigint(20) unsigned NOT NULL auto_increment,
  `logged_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `event_type_id` smallint(6) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `subject_id` int(10) unsigned NOT NULL default '0',
  `store_id` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`event_id`),
  KEY `subject_id` (`subject_id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('report_event_types')} DROP INDEX `event_type_id`;
ALTER TABLE {$this->getTable('report_event_types')} CHANGE `event_name` `event_name` varchar(64) NOT NULL;
UPDATE {$this->getTable('report_event_types')} SET `event_name`='catalog_product_compare_add_product' WHERE `event_type_id`=3;
ALTER TABLE {$this->getTable('report_event')} ADD `sybtype` tinyint(3) unsigned NOT NULL default '0' AFTER `subject_id`;
ALTER TABLE {$this->getTable('report_event')} ADD INDEX (`event_type_id`);
ALTER TABLE {$this->getTable('report_event')} ADD INDEX (`sybtype`);
ALTER TABLE {$this->getTable('report_event')} ADD INDEX (`store_id`);
ALTER TABLE {$this->getTable('report_event_types')} ADD `customer_login` TINYINT UNSIGNED NOT NULL DEFAULT '0';
UPDATE {$this->getTable('report_event_types')} SET `customer_login`=1;

ALTER TABLE {$this->getTable('report_event')} CHANGE `sybtype` `subtype` tinyint(3) unsigned NOT NULL default '0' AFTER `subject_id`;
ALTER TABLE {$this->getTable('report_event')} DROP INDEX `sybtype`, ADD INDEX (`subtype`);

ALTER TABLE {$this->getTable('report_event')}
    DROP INDEX `event_type_id`,
    ADD INDEX `IDX_EVENT_TYPE` (`event_type_id`);
ALTER TABLE {$this->getTable('report_event')}
    ADD CONSTRAINT `FK_REPORT_EVENT_TYPE` FOREIGN KEY (`event_type_id`)
    REFERENCES {$this->getTable('report_event_types')} (`event_type_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;
ALTER TABLE {$this->getTable('report_event')}
    DROP INDEX `subject_id`,
    ADD INDEX `IDX_SUBJECT` (`subject_id`);
ALTER TABLE {$this->getTable('report_event')}
    DROP INDEX `object_id`,
    ADD INDEX `IDX_OBJECT` (`object_id`);
ALTER TABLE {$this->getTable('report_event')}
    DROP INDEX `subtype`,
    ADD INDEX `IDX_SUBTYPE` (`subtype`);
ALTER TABLE {$this->getTable('report_event')}
    DROP INDEX `store_id`;
ALTER TABLE {$this->getTable('report_event')}
    CHANGE `store_id` `store_id` smallint(5) unsigned NOT NULL;
ALTER TABLE {$this->getTable('report_event')}
    ADD CONSTRAINT `FK_REPORT_EVENT_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

UPDATE {$installer->getTable('cms_page')} SET `layout_update_xml` = CONCAT(IFNULL(layout_update_xml, ''), '<!--<reference name=\"content\">
<block type=\"catalog/product_new\" name=\"home.catalog.product.new\" alias=\"product_new\" template=\"catalog/product/new.phtml\" after=\"cms_page\"/>
<block type=\"reports/product_viewed\" name=\"home.reports.product.viewed\" alias=\"product_viewed\" template=\"reports/home_product_viewed.phtml\" after=\"product_new\"/>
<block type=\"reports/product_compared\" name=\"home.reports.product.compared\" template=\"reports/home_product_compared.phtml\" after=\"product_viewed\" />
</reference><reference name=\"right\">
<action method=\"unsetChild\"><alias>right.reports.product.viewed</alias></action>
<action method=\"unsetChild\"><alias>right.reports.product.compared</alias></action>
</reference>-->') WHERE `identifier`='home';

CREATE TABLE `{$installer->getTable('reports/viewed_product_index')}` (
  `index_id` bigint(20) unsigned NOT NULL auto_increment,
  `visitor_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned default NULL,
  `product_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned default NULL,
  `added_at` datetime NOT NULL,
  PRIMARY KEY  (`index_id`),
  UNIQUE KEY `UNQ_BY_VISITOR` (`visitor_id`,`product_id`),
  UNIQUE KEY `UNQ_BY_CUSTOMER` (`customer_id`,`product_id`),
  KEY `IDX_STORE` (`store_id`),
  KEY `IDX_SORT_ADDED_AT` (`added_at`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `FK_REPORT_VIEWED_PRODUCT_INDEX_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_REPORT_VIEWED_PRODUCT_INDEX_CUSTOMER` FOREIGN KEY (`customer_id`) REFERENCES `{$installer->getTable('customer/entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_REPORT_VIEWED_PRODUCT_INDEX_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('reports/compared_product_index')}` (
  `index_id` bigint(20) unsigned NOT NULL auto_increment,
  `visitor_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned default NULL,
  `product_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned default NULL,
  `added_at` datetime NOT NULL,
  PRIMARY KEY  (`index_id`),
  UNIQUE KEY `UNQ_BY_VISITOR` (`visitor_id`,`product_id`),
  UNIQUE KEY `UNQ_BY_CUSTOMER` (`customer_id`,`product_id`),
  KEY `IDX_STORE` (`store_id`),
  KEY `IDX_SORT_ADDED_AT` (`added_at`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `FK_REPORT_COMPARED_PRODUCT_INDEX_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_REPORT_COMPARED_PRODUCT_INDEX_CUSTOMER` FOREIGN KEY (`customer_id`) REFERENCES `{$installer->getTable('customer/entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_REPORT_COMPARED_PRODUCT_INDEX_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$oldLayout = $installer->getConnection()->fetchOne("SELECT layout_update_xml FROM {$installer->getTable('cms_page')} WHERE `identifier`='home' LIMIT 1");
$newLayout = str_replace(array(
    '<block type="catalog/product_new" name="home.catalog.product.new" alias="product_new" template="catalog/product/new.phtml" after="cms_page"/>',
    '<block type="reports/product_viewed" name="home.reports.product.viewed" alias="product_viewed" template="reports/home_product_viewed.phtml" after="product_new"/>',
    '<block type="reports/product_compared" name="home.reports.product.compared" template="reports/home_product_compared.phtml" after="product_viewed" />',
    ), array(
        '<block type="catalog/product_new" name="home.catalog.product.new" alias="product_new" template="catalog/product/new.phtml" after="cms_page"><action method="addPriceBlockType"><type>bundle</type><block>bundle/catalog_product_price</block><template>bundle/catalog/product/price.phtml</template></action></block>',
        '<block type="reports/product_viewed" name="home.reports.product.viewed" alias="product_viewed" template="reports/home_product_viewed.phtml" after="product_new"><action method="addPriceBlockType"><type>bundle</type><block>bundle/catalog_product_price</block><template>bundle/catalog/product/price.phtml</template></action></block>',
        '<block type="reports/product_compared" name="home.reports.product.compared" template="reports/home_product_compared.phtml" after="product_viewed"><action method="addPriceBlockType"><type>bundle</type><block>bundle/catalog_product_price</block><template>bundle/catalog/product/price.phtml</template></action></block>',
    ), $oldLayout
);

$installer->run(sprintf("UPDATE {$installer->getTable('cms_page')} SET `layout_update_xml` = %s WHERE `identifier`='home';",
    $installer->getConnection()->quote($newLayout)
));

$installer->run("ALTER TABLE {$this->getTable('report_viewed_product_index')} CHANGE `visitor_id` `visitor_id` INT( 10 ) UNSIGNED NULL ");
$installer->getConnection()->modifyColumn($installer->getTable('reports/compared_product_index'), 'visitor_id',
    'INT(10) UNSIGNED NULL');
$installer->endSetup();
