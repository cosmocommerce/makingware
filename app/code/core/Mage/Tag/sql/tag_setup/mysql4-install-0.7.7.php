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
 * @package     Mage_Tag
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('tag')};
CREATE TABLE {$this->getTable('tag')} (
  `tag_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('tag_relation')};
CREATE TABLE {$this->getTable('tag_relation')} (
  `tag_relation_id` int(11) unsigned NOT NULL auto_increment,
  `tag_id` int(11) unsigned NOT NULL default '0',
  `customer_id` int(10) unsigned NOT NULL default '0',
  `product_id` int(11) unsigned NOT NULL default '0',
  `store_id` smallint(6) unsigned NOT NULL default '1',
  `active` tinyint (1) unsigned NOT NULL default '1',
  `created_at` datetime default NULL,
  PRIMARY KEY (`tag_relation_id`),
  KEY `FK_TAG_RELATION_TAG` (`tag_id`),
  KEY `FK_TAG_RELATION_CUSTOMER` (`customer_id`),
  KEY `FK_TAG_RELATION_PRODUCT` (`product_id`),
  KEY `FK_TAG_RELATION_STORE` (`store_id`),
  CONSTRAINT `FK_TAG_RELATION_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tag_relation_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES {$this->getTable('tag')} (`tag_id`) ON DELETE CASCADE,
  CONSTRAINT `tag_relation_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES {$this->getTable('customer_entity')} (`entity_id`) ON DELETE CASCADE,
  CONSTRAINT `tag_relation_ibfk_4` FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core_store')} (`store_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('tag_summary')};
CREATE TABLE {$this->getTable('tag_summary')} (
   `tag_id` int(11) unsigned NOT NULL default '0',
   `store_id` smallint(5) unsigned NOT NULL default '0',
   `customers` int(11) unsigned NOT NULL default '0',
   `products` int(11) unsigned NOT NULL default '0',
   `uses` int(11) unsigned NOT NULL default '0',
   `historical_uses` int(11) unsigned NOT NULL default '0',
   `popularity` int(11) unsigned NOT NULL default '0',
   PRIMARY KEY  (`tag_id`,`store_id`),
   CONSTRAINT `TAG_SUMMARY_TAG` FOREIGN KEY (`tag_id`) REFERENCES {$this->getTable('tag')} (`tag_id`) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('tag_summary')}
    ADD CONSTRAINT `FK_TAG_SUMMARY_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

    ");

$purgeFk = array(
    $installer->getTable('tag/relation') => array(
        'product_id', 'tag_id', 'customer_id', 'store_id'
    ),
    $installer->getTable('tag/summary') => array(
        'tag_id'
    ),
);
$purgeIndex = array(
    array(
        $installer->getTable('tag/relation'),
        array('product_id')
    ),
    array(
        $installer->getTable('tag/relation'),
        array('tag_id')
    ),
    array(
        $installer->getTable('tag/relation'),
        array('customer_id')
    ),
    array(
        $installer->getTable('tag/relation'),
        array('store_id')
    ),
    array(
        $installer->getTable('tag/summary'),
        array('tag_id')
    ),
);
foreach ($purgeFk as $tableName => $columns) {
    $foreignKeys = $installer->getConnection()->getForeignKeys($tableName);
    foreach ($foreignKeys as $fkProp) {
        if (in_array($fkProp['COLUMN_NAME'], $columns)) {
            $installer->getConnection()
                ->dropForeignKey($tableName, $fkProp['FK_NAME']);
        }
    }
}

foreach ($purgeIndex as $prop) {
    list($tableName, $columns) = $prop;
    $indexList = $installer->getConnection()->getIndexList($tableName);
    foreach ($indexList as $indexProp) {
        if ($columns === $indexProp['COLUMNS_LIST']) {
            $installer->getConnection()->dropKey($tableName, $indexProp['KEY_NAME']);
        }
    }
}

$installer->getConnection()->addKey($installer->getTable('tag/relation'),
    'IDX_PRODUCT', 'product_id');
$installer->getConnection()->addKey($installer->getTable('tag/relation'),
    'IDX_TAG', 'tag_id');
$installer->getConnection()->addKey($installer->getTable('tag/relation'),
    'IDX_CUSTOMER', 'customer_id');
$installer->getConnection()->addKey($installer->getTable('tag/relation'),
    'IDX_STORE', 'store_id');
$installer->getConnection()->addKey($installer->getTable('tag/summary'),
    'IDX_TAG', 'tag_id');

$installer->getConnection()->addConstraint('FK_TAG_RELATION_PRODUCT',
    $installer->getTable('tag/relation'), 'product_id',
    $installer->getTable('catalog/product'), 'entity_id',
    'CASCADE', 'CASCADE', true);
$installer->getConnection()->addConstraint('FK_TAG_RELATION_TAG',
    $installer->getTable('tag/relation'), 'tag_id',
    $installer->getTable('tag/tag'), 'tag_id',
    'CASCADE', 'CASCADE', true);
$installer->getConnection()->addConstraint('FK_TAG_RELATION_CUSTOMER',
    $installer->getTable('tag/relation'), 'customer_id',
    $installer->getTable('customer/entity'), 'entity_id',
    'CASCADE', 'CASCADE', true);
$installer->getConnection()->addConstraint('FK_TAG_RELATION_STORE',
    $installer->getTable('tag/relation'), 'store_id',
    $installer->getTable('core/store'), 'store_id',
    'CASCADE', 'CASCADE', true);
$installer->getConnection()->addConstraint('FK_TAG_SUMMARY_TAG',
    $installer->getTable('tag/summary'), 'tag_id',
    $installer->getTable('tag/tag'), 'tag_id',
    'CASCADE', 'CASCADE', true);

$installer->getConnection()->addColumn($this->getTable('tag_summary'), 'base_popularity',
    'int(11) UNSIGNED DEFAULT \'0\' NOT NULL AFTER `popularity`'
);

$installer->getConnection()->changeColumn($this->getTable('tag_relation'), 'customer_id', 'customer_id',
    'INT(10) UNSIGNED NULL DEFAULT NULL'
);

$installer->getConnection()->addColumn($installer->getTable('tag/tag'), 'first_customer_id', "INT(10) UNSIGNED NOT NULL DEFAULT '0'");

$groupedTags = $installer->getConnection()->select()
    ->from($installer->getTable('tag/relation'))->group('tag_id')->order('created_at ASC');
$select = $installer->getConnection()->select()
    ->reset()
    ->joinInner(array('relation_table' => new Zend_Db_Expr("({$groupedTags->__toString()})")),
        'relation_table.tag_id = main_table.tag_id', null)
    ->columns(array('first_customer_id' => 'customer_id'));

$updateSql = $select->crossUpdateFromSelect(array('main_table' => $installer->getTable('tag/tag')));
$installer->getConnection()->query($updateSql);

$installer->getConnection()->addColumn($installer->getTable('tag/tag'), 'first_store_id', "smallint(5) UNSIGNED NOT NULL DEFAULT '0'");

$groupedTags = $installer->getConnection()->select()
    ->from($installer->getTable('tag/relation'))->group('tag_id')->order('created_at ASC');
$select = $installer->getConnection()->select()
    ->reset()
    ->joinInner(array('relation_table' => new Zend_Db_Expr("({$groupedTags->__toString()})")),
        'relation_table.tag_id = main_table.tag_id', null)
    ->columns(array('first_store_id' => 'store_id'));

$updateSql = $select->crossUpdateFromSelect(array('main_table' => $installer->getTable('tag/tag')));
$installer->getConnection()->query($updateSql);

$deprecatedComment = 'deprecated since 1.4.0.1';

$installer->getConnection()->modifyColumn(
    $installer->getTable('tag/summary'), 'uses', "int(11) unsigned NOT NULL default '0' COMMENT '{$deprecatedComment}'"
);
$installer->getConnection()->modifyColumn(
    $installer->getTable('tag/summary'), 'historical_uses',
    "int(11) unsigned NOT NULL default '0' COMMENT '{$deprecatedComment}'"
);
$installer->getConnection()->modifyColumn(
    $installer->getTable('tag/summary'), 'base_popularity',
    "int(11) UNSIGNED DEFAULT '0' NOT NULL COMMENT '{$deprecatedComment}'"
);

$installer->run("
    CREATE TABLE {$this->getTable('tag/properties')} (
       `tag_id` int(11) unsigned NOT NULL default '0',
       `store_id` smallint(5) unsigned NOT NULL default '0',
       `base_popularity` int(11) unsigned NOT NULL default '0',
       PRIMARY KEY (`tag_id`,`store_id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->getConnection()->addConstraint(
    'TAG_PROPERTIES_TAG',
    $installer->getTable('tag/properties'),
    'tag_id',
    $installer->getTable('tag/tag'),
    'tag_id'
);

$installer->getConnection()->addConstraint(
    'TAG_PROPERTIES_STORE',
    $installer->getTable('tag/properties'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id'
);

$installer->getConnection()
    ->addKey(
        $this->getTable('tag/relation'),
        'UNQ_TAG_CUSTOMER_PRODUCT_STORE',
        array('tag_id', 'customer_id', 'product_id', 'store_id'),
        'unique'
    );
$installer->endSetup();
