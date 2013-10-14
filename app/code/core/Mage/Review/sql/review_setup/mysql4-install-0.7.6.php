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
 * @package     Mage_Review
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('review')};
CREATE TABLE {$this->getTable('review')} (
  `review_id` bigint(20) unsigned NOT NULL auto_increment,
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `entity_id` smallint(5) unsigned NOT NULL default '0',
  `entity_pk_value` int(10) unsigned NOT NULL default '0',
  `status_id` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`review_id`),
  KEY `FK_REVIEW_ENTITY` (`entity_id`),
  KEY `FK_REVIEW_STATUS` (`status_id`),
  KEY `FK_REVIEW_PARENT_PRODUCT` (`entity_pk_value`),
  CONSTRAINT `FK_REVIEW_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$this->getTable('review_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_REVIEW_PARENT_PRODUCT` FOREIGN KEY (`entity_pk_value`) REFERENCES {$this->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_REVIEW_STATUS` FOREIGN KEY (`status_id`) REFERENCES {$this->getTable('review_status')} (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Review base information';

-- DROP TABLE IF EXISTS {$this->getTable('review_detail')};
CREATE TABLE {$this->getTable('review_detail')} (
  `detail_id` bigint(20) unsigned NOT NULL auto_increment,
  `review_id` bigint(20) unsigned NOT NULL default '0',
  `store_id` smallint(6) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `detail` text NOT NULL,
  `nickname` varchar(128) NOT NULL default '',
  `customer_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`detail_id`),
  KEY `FK_REVIEW_DETAIL_REVIEW` (`review_id`),
  CONSTRAINT `FK_REVIEW_DETAIL_REVIEW` FOREIGN KEY (`review_id`) REFERENCES {$this->getTable('review')} (`review_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Review detail information';

-- DROP TABLE IF EXISTS {$this->getTable('review_entity')};
CREATE TABLE {$this->getTable('review_entity')} (
  `entity_id` smallint(5) unsigned NOT NULL auto_increment,
  `entity_code` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Review entities';

insert  into {$this->getTable('review_entity')}(`entity_id`,`entity_code`) values (1,'product'),(2,'customer'),(3,'category');

-- DROP TABLE IF EXISTS {$this->getTable('review_entity_summary')};
CREATE TABLE {$this->getTable('review_entity_summary')} (
  `primary_id` bigint(20) NOT NULL auto_increment,
  `entity_pk_value` bigint(20) NOT NULL default '0',
  `entity_type` tinyint(4) NOT NULL default '0',
  `reviews_count` smallint(6) NOT NULL default '0',
  `rating_summary` tinyint(4) NOT NULL default '0',
  `store_id` smallint (5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`primary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('review_status')};
CREATE TABLE {$this->getTable('review_status')} (
  `status_id` tinyint(3) unsigned NOT NULL auto_increment,
  `status_code` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Review statuses';

insert  into {$this->getTable('review_status')}(`status_id`,`status_code`) values (1,'Approved'),(2,'Pending'),(3,'Not Approved');

-- DROP TABLE IF EXISTS `{$this->getTable('review_store')}`;
CREATE TABLE `{$this->getTable('review_store')}` (
  `review_id` bigint(20) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`review_id`,`store_id`),
  CONSTRAINT `FK_REVIEW_STORE_REVIEW` FOREIGN KEY (`review_id`) REFERENCES `{$this->getTable('review')}` (`review_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->run("
ALTER TABLE {$this->getTable('review_detail')}
    CHANGE `store_id` `store_id` smallint(5) unsigned NULL DEFAULT '0';
ALTER TABLE {$this->getTable('review_detail')}
    ADD CONSTRAINT `FK_REVIEW_DETAIL_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL;
");
$installer->run("
ALTER TABLE {$this->getTable('review_entity_summary')}
    ADD CONSTRAINT `FK_REVIEW_ENTITY_SUMMARY_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;
");
$installer->run("
ALTER TABLE {$this->getTable('review_store')}
    ADD CONSTRAINT `FK_REVIEW_STORE_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;
");

$voteTable   = $this->getTable('rating_option_vote');
$reviewTable = $this->getTable('review');

$this->run("
DELETE FROM `{$voteTable}` WHERE `review_id` NOT IN (SELECT review_id FROM `{$reviewTable}`);
");

$this->run("
ALTER TABLE `{$voteTable}`
ADD CONSTRAINT `FK_RATING_OPTION_REVIEW_ID` FOREIGN KEY (`review_id`) REFERENCES `{$reviewTable}` (`review_id`)
ON DELETE CASCADE ON UPDATE CASCADE;
");

// add average approved percent
$this->run("
ALTER TABLE `{$this->getTable('rating_option_vote_aggregated')}`
ADD COLUMN `percent_approved` tinyint(3) NULL DEFAULT 0 AFTER `percent`;
");

try {
    // re-aggregate existing reviews
    $resource = Mage::getResourceSingleton('review/review');
    // count quantity and aggregate packs per 100 items
    $total = $this->getConnection()->select()->from($this->getTable('review'), 'count(*)');
    $total = intval($this->getConnection()->fetchOne($total));
    for ($i = 0; $i < $total; $i += 100) {
        $select = $this->getConnection()->select()
            ->from($this->getTable('review'), array('review_id', 'entity_pk_value'))
            ->limit(100, $i)
        ;
        $rows = $this->getConnection()->fetchAll($select);
        foreach ($rows as $row) {
            $resource->reAggregateReview($row['review_id'], $row['entity_pk_value']);
        }
    }
}
catch (Exception $e) {
    $this->run("ALTER TABLE `{$this->getTable('rating_option_vote_aggregated')}` DROP COLUMN `percent_approved`;");
    throw $e;
}

/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();
$installer->getConnection()->addConstraint('FK_REVIEW_STORE_REVIEW',
    $installer->getTable('review/review_store'), 'review_id',
    $installer->getTable('review/review'), 'review_id',
    'CASCADE', 'CASCADE', true);

$tableReviewDetail = $installer->getTable('review/review_detail');
$tableCustomer = $installer->getTable('customer_entity');

$installer->run("UPDATE {$tableReviewDetail} SET customer_id=NULL WHERE customer_id NOT IN (SELECT entity_id FROM {$tableCustomer})");

$installer->getConnection()->addConstraint('FK_REVIEW_DETAIL_CUSTOMER',
    $tableReviewDetail, 'customer_id',
    $tableCustomer, 'entity_id',
    'SET NULL', 'CASCADE', true);

$installer->getConnection()->dropForeignKey($this->getTable('review'), 'FK_REVIEW_PARENT_PRODUCT');

$installer->endSetup();
