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
 * @package     Mage_CatalogRule
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('catalogrule')};
CREATE TABLE {$this->getTable('catalogrule')} (
  `rule_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `from_date` date default NULL,
  `to_date` date default NULL,
  `store_ids` varchar(255) NOT NULL default '',
  `customer_group_ids` varchar(255) NOT NULL default '',
  `is_active` tinyint(1) NOT NULL default '0',
  `conditions_serialized` text NOT NULL,
  `actions_serialized` text NOT NULL,
  `stop_rules_processing` tinyint(1) NOT NULL default '1',
  `sort_order` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rule_id`),
  KEY `sort_order` (`is_active`,`sort_order`,`to_date`,`from_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- DROP TABLE IF EXISTS {$this->getTable('catalogrule_product')};
CREATE TABLE {$this->getTable('catalogrule_product')} (
  `rule_product_id` int(10) unsigned NOT NULL auto_increment,
  `rule_id` int(10) unsigned NOT NULL default '0',
  `from_time` int(10) unsigned NOT NULL default '0',
  `to_time` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `customer_group_id` smallint(5) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `action_operator` enum('to_fixed','to_percent','by_fixed','by_percent') NOT NULL default 'to_fixed',
  `action_amount` decimal(12,4) NOT NULL default '0.0000',
  `action_stop` tinyint(1) NOT NULL default '0',
  `sort_order` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rule_product_id`),
  UNIQUE KEY `sort_order` (`from_time`,`to_time`,`store_id`,`customer_group_id`,`product_id`,`sort_order`),
  KEY `FK_catalogrule_product_rule` (`rule_id`),
  KEY `FK_catalogrule_product_store` (`store_id`),
  KEY `FK_catalogrule_product_customergroup` (`customer_group_id`),
  CONSTRAINT `FK_catalogrule_product_customergroup` FOREIGN KEY (`customer_group_id`) REFERENCES {$this->getTable('customer_group')} (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_catalogrule_product_rule` FOREIGN KEY (`rule_id`) REFERENCES {$this->getTable('catalogrule')} (`rule_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_catalogrule_product_store` FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('catalogrule_product_price')};
CREATE TABLE {$this->getTable('catalogrule_product_price')} (
  `rule_product_price_id` int(10) unsigned NOT NULL auto_increment,
  `rule_date` date NOT NULL default '0000-00-00',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `customer_group_id` smallint(5) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `rule_price` decimal(12,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`rule_product_price_id`),
  UNIQUE KEY `rule_date` (`rule_date`,`store_id`,`customer_group_id`,`product_id`),
  KEY `FK_catalogrule_product_price_store` (`store_id`),
  KEY `FK_catalogrule_product_price_customergroup` (`customer_group_id`),
  CONSTRAINT `FK_catalogrule_product_price_customergroup` FOREIGN KEY (`customer_group_id`) REFERENCES {$this->getTable('customer_group')} (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_catalogrule_product_price_store` FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('catalogrule_affected_product')} (
  `product_id` int(10) unsigned NOT NULL,
  KEY `IDX_PRODUCT` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$this->getTable('catalogrule_affected_product')}`;

CREATE TABLE `{$this->getTable('catalogrule_affected_product')}` (
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->getConnection()->addColumn($this->getTable('catalogrule'), 'simple_action', 'varchar(32) not null');
$installer->getConnection()->addColumn($this->getTable('catalogrule'), 'discount_amount', 'decimal(12,4) not null');
$conn = $installer->getConnection();
$websites = $conn->fetchPairs("SELECT store_id, website_id FROM {$installer->getTable('core_store')}");
$ruleTable = $this->getTable('catalogrule');
if ($conn->tableColumnExists($ruleTable, 'store_ids')) {
    // catalogrule
    $conn->addColumn($ruleTable, 'website_ids', 'text');
    $select = $conn->select()
        ->from($ruleTable, array('rule_id', 'store_ids'));
    $rows = $conn->fetchAll($select);

    foreach ($rows as $r) {
        $websiteIds = array();
        foreach (explode(',', $r['store_ids']) as $storeId) {
            if (($storeId!=='') && isset($websites[$storeId])) {
                $websiteIds[$websites[$storeId]] = true;
            }
        }

        $conn->update($ruleTable, array('website_ids'=>join(',',array_keys($websiteIds))), "rule_id=".$r['rule_id']);
    }
    $conn->dropColumn($ruleTable, 'store_ids');
}

// catalogrule_product
$ruleProductTable = $this->getTable('catalogrule_product');
if ($conn->tableColumnExists($ruleProductTable, 'store_id')) {
    $conn->addColumn($ruleProductTable, 'website_id', 'smallint unsigned not null');
    $unique = array();

    $select = $conn->select()
        ->from($ruleProductTable);
    $rows = $conn->fetchAll($select);

    //$q = $conn->query("select * from `$ruleProductTable`");
    foreach ($rows as $r) {
        $websiteId = $websites[$r['store_id']];
        $key = $r['from_time'].'|'.$r['to_time'].'|'.$websiteId.'|'.$r['customer_group_id'].'|'.$r['product_id'].'|'.$r['sort_order'];
        if (isset($unique[$key])) {
            $conn->delete($ruleProductTable, $conn->quoteInto("rule_product_id=?", $r['rule_product_id']));
        } else {
            $conn->update($ruleProductTable, array('website_id'=>$websiteId), "rule_product_id=".$r['rule_product_id']);
            $unique[$key] = true;
        }
    }
    $conn->dropKey($ruleProductTable, 'sort_order');
    $conn->raw_query("ALTER TABLE `$ruleProductTable` ADD UNIQUE KEY `sort_order` (`from_time`,`to_time`,`website_id`,`customer_group_id`,`product_id`,`sort_order`)");

    $conn->dropForeignKey($ruleProductTable, 'FK_catalogrule_product_store');
    $conn->dropColumn($ruleProductTable, 'store_id');

    $conn->dropForeignKey($ruleProductTable, 'FK_catalogrule_product_website');
    $conn->raw_query("ALTER TABLE `$ruleProductTable` ADD CONSTRAINT `FK_catalogrule_product_website` FOREIGN KEY (`website_id`) REFERENCES `{$this->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE");
}


// catalogrule_product_price
$ruleProductPriceTable = $this->getTable('catalogrule_product_price');
if ($conn->tableColumnExists($ruleProductPriceTable, 'store_id')) {
    $conn->addColumn($ruleProductPriceTable, 'website_id', 'smallint unsigned not null');
    $conn->delete($ruleProductPriceTable);

    $conn->dropKey($ruleProductPriceTable, 'rule_date');
    $conn->raw_query("ALTER TABLE `$ruleProductPriceTable` ADD UNIQUE KEY `rule_date` (`rule_date`,`website_id`,`customer_group_id`,`product_id`)");

    $conn->dropForeignKey($ruleProductPriceTable, 'FK_catalogrule_product_store');
    $conn->dropColumn($ruleProductPriceTable, 'store_id');

    $conn->dropForeignKey($ruleProductPriceTable, 'FK_catalogrule_product_price_website');
    $conn->raw_query("ALTER TABLE `$ruleProductPriceTable` ADD CONSTRAINT `FK_catalogrule_product_price_website` FOREIGN KEY (`website_id`) REFERENCES `{$this->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE");
}
$installer->getConnection()->addColumn($this->getTable('catalogrule_product_price'), 'latest_start_date', 'date');
$installer->getConnection()->addColumn($this->getTable('catalogrule_product_price'), 'earliest_end_date', 'date');
$installer->getConnection()->changeColumn($this->getTable('catalogrule'),
    'conditions_serialized', 'conditions_serialized',
    'mediumtext CHARACTER SET utf8 NOT NULL'
);
$installer->getConnection()->changeColumn($this->getTable('catalogrule'),
    'actions_serialized', 'actions_serialized',
    'mediumtext CHARACTER SET utf8 NOT NULL'
);
$installer->getConnection()->addKey(
    $installer->getTable('catalogrule_product'),
    'sort_order',
    array('rule_id', 'from_time','to_time','website_id','customer_group_id','product_id','sort_order'),
    'unique'
);

$ruleGroupWebsiteTable = $installer->getTable('catalogrule/rule_group_website');

$installer->run("CREATE TABLE `{$ruleGroupWebsiteTable}` (
 `rule_id` int(10) unsigned NOT NULL default '0',
 `customer_group_id` smallint(5) unsigned default NULL,
 `website_id` smallint(5) unsigned default NULL,
 KEY `rule_id` (`rule_id`),
 KEY `customer_group_id` (`customer_group_id`),
 KEY `website_id` (`website_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin");

$installer->getConnection()->addConstraint(
    'FK_CATALOGRULE_GROUP_WEBSITE_RULE', $ruleGroupWebsiteTable, 'rule_id',
    $installer->getTable('catalogrule/rule'), 'rule_id', 'CASCADE', 'CASCADE'
);
$installer->getConnection()->addConstraint(
    'FK_CATALOGRULE_GROUP_WEBSITE_GROUP', $ruleGroupWebsiteTable, 'customer_group_id',
    $installer->getTable('customer/customer_group'), 'customer_group_id', 'CASCADE', 'CASCADE'
);
$installer->getConnection()->addConstraint(
    'FK_CATALOGRULE_GROUP_WEBSITE_WEBSITE', $ruleGroupWebsiteTable, 'website_id',
    $installer->getTable('core/website'), 'website_id', 'CASCADE', 'CASCADE'
);

$installer->run("ALTER TABLE `{$ruleGroupWebsiteTable}` ADD PRIMARY KEY ( `rule_id` , `customer_group_id`, `website_id` )");

$connection = $installer->getConnection();
$connection->addKey($installer->getTable('catalogrule/rule_product'), 'IDX_FROM_TIME', 'from_time');
$connection->addKey($installer->getTable('catalogrule/rule_product'), 'IDX_TO_TIME', 'to_time');
$installer->getConnection()
    ->modifyColumn($this->getTable('catalogrule'),
        'customer_group_ids',
        'TEXT');
$installer->endSetup();
