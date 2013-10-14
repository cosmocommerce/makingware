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
 * @package     Mage_Core
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
CREATE TABLE `{$installer->getTable('core_resource')}` (
  `code` varchar(50) NOT NULL default '',
  `version` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Resource version registry';

CREATE TABLE `{$installer->getTable('core_website')}` (
  `website_id` smallint(5) unsigned NOT NULL auto_increment,
  `code` varchar(32) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `sort_order` smallint(5) unsigned NOT NULL default '0',
  `default_group_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`website_id`),
  UNIQUE KEY `code` (`code`),
  KEY `sort_order` (`sort_order`),
  KEY `default_group_id` (`default_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Websites';

INSERT INTO `{$installer->getTable('core_website')}` VALUES
    (0, 'admin', 'Admin', 0, 0),
    (1, 'base', 'Main Website', 0, 1);

-- DROP TABLE IF EXISTS `{$installer->getTable('core_store_group')}`;
CREATE TABLE `{$installer->getTable('core_store_group')}` (
  `group_id` smallint(5) unsigned NOT NULL auto_increment,
  `website_id` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `root_category_id` int(10) unsigned NOT NULL default '0',
  `default_store_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`),
  KEY `FK_STORE_GROUP_WEBSITE` (`website_id`),
  KEY `default_store_id` (`default_store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$installer->getTable('core_store_group')}` VALUES
    (0, 0, 'Default', 0, 0),
    (1, 1, 'Main Website Store', 2, 1);

-- DROP TABLE IF EXISTS `{$installer->getTable('core_store')}`;
CREATE TABLE `{$installer->getTable('core_store')}` (
  `store_id` smallint(5) unsigned NOT NULL auto_increment,
  `code` varchar(32) NOT NULL default '',
  `website_id` smallint(5) unsigned default '0',
  `group_id` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `sort_order` smallint(5) unsigned NOT NULL default '0',
  `is_active` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`store_id`),
  UNIQUE KEY `code` (`code`),
  KEY `FK_STORE_WEBSITE` (`website_id`),
  KEY `is_active` (`is_active`,`sort_order`),
  KEY `FK_STORE_GROUP` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores';

INSERT INTO `{$installer->getTable('core_store')}` VALUES
    (0, 'admin', 0, 0, 'Admin', 0, 1),
    (1, 'default', 1, 1, 'Default Store View', 0, 1);

CREATE TABLE `{$installer->getTable('core_config_data')}` (
  `config_id` int(10) unsigned NOT NULL auto_increment,
  `scope` enum('default','websites','stores','config') NOT NULL default 'default',
  `scope_id` int(11) NOT NULL default '0',
  `path` varchar(255) NOT NULL default 'general',
  `value` text NOT NULL,
  PRIMARY KEY  (`config_id`),
  UNIQUE KEY `config_scope` (`scope`,`scope_id`,`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('core_email_template')}` (
    `template_id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `template_code` varchar(150) DEFAULT NULL,
  `template_text` text,
  `template_styles` text,
  `template_type` int(3) unsigned DEFAULT NULL,
  `template_subject` varchar(200) DEFAULT NULL,
  `template_sender_name` varchar(200) DEFAULT NULL,
  `template_sender_email` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `added_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `orig_template_code` varchar(200) DEFAULT NULL,
  `orig_template_variables` text NOT NULL,
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `template_code` (`template_code`),
  KEY `added_at` (`added_at`),
  KEY `modified_at` (`modified_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Email templates';

CREATE TABLE `{$installer->getTable('core_layout_update')}` (
  `layout_update_id` int(10) unsigned NOT NULL auto_increment,
  `handle` varchar(255) default NULL,
  `xml` text,
  PRIMARY KEY  (`layout_update_id`),
  KEY `handle` (`handle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('core_layout_link')}` (
  `layout_link_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `package` varchar(64) NOT NULL default '',
  `theme` varchar(64) NOT NULL default '',
  `layout_update_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`layout_link_id`),
  UNIQUE KEY `store_id` (`store_id`,`package`,`theme`,`layout_update_id`),
  KEY `FK_core_layout_link_update` (`layout_update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('core_session')}` (
  `session_id` varchar(255) NOT NULL default '',
  `website_id` smallint(5) unsigned default NULL,
  `session_expires` int(10) unsigned NOT NULL default '0',
  `session_data` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `FK_SESSION_WEBSITE` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Session data store';

CREATE TABLE `{$installer->getTable('core_translate')}` (
  `key_id` int(10) unsigned NOT NULL auto_increment,
  `string` varchar(255) NOT NULL default '',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `translate` varchar(255) NOT NULL default '',
  `locale` varchar(20) NOT NULL default 'en_US',
  PRIMARY KEY  (`key_id`),
  UNIQUE KEY `IDX_CODE` (`store_id`,`locale`,`string`),
  KEY `FK_CORE_TRANSLATE_STORE` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Translation data';

CREATE TABLE `{$installer->getTable('core_url_rewrite')}` (
  `url_rewrite_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `id_path` varchar(255) NOT NULL default '',
  `request_path` varchar(255) NOT NULL default '',
  `target_path` varchar(255) NOT NULL default '',
  `options` varchar(255) NOT NULL default '',
  `type` int(1) NOT NULL default '0',
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`url_rewrite_id`),
  UNIQUE KEY `id_path` (`id_path`,`store_id`),
  UNIQUE KEY `request_path` (`request_path`,`store_id`),
  KEY `target_path` (`target_path`,`store_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('core_url_rewrite_tag')}` (
  `url_rewrite_tag_id` int(10) unsigned NOT NULL auto_increment,
  `url_rewrite_id` int(10) unsigned NOT NULL default '0',
  `tag` varchar(255) default NULL,
  PRIMARY KEY  (`url_rewrite_tag_id`),
  UNIQUE KEY `tag` (`tag`,`url_rewrite_id`),
  KEY `url_rewrite_id` (`url_rewrite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('design_change')}` (
  `design_change_id` int(11) NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `design` varchar(255) NOT NULL default '',
  `date_from` date NOT NULL default '0000-00-00',
  `date_to` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`design_change_id`),
  KEY `FK_DESIGN_CHANGE_STORE` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('core_flag')}` (
  `flag_id` smallint(5) unsigned NOT NULL auto_increment,
  `flag_code` varchar(255) NOT NULL,
  `state` smallint(5) unsigned NOT NULL default '0',
  `flag_data` text,
  `last_update` datetime NOT NULL,
  PRIMARY KEY  (`flag_id`),
  KEY (`last_update`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('core_email_variable')}` (
  `variable_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`variable_id`),
  UNIQUE KEY `IDX_CODE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('core_email_variable_value')}` (
  `value_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `variable_id` int(11) unsigned NOT NULL DEFAULT '0',
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`value_id`),
  UNIQUE KEY `IDX_VARIABLE_STORE` (`variable_id`,`store_id`),
  KEY `IDX_VARIABLE_ID` (`variable_id`),
  KEY `IDX_STORE_ID` (`store_id`),
  CONSTRAINT `FK_CORE_EMAIL_VARIABLE_VALUE_STORE_ID` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CORE_EMAIL_VARIABLE_VALUE_VARIABLE_ID` FOREIGN KEY (`variable_id`) REFERENCES `{$installer->getTable('core_email_variable')}` (`variable_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('core/cache')}` (
        `id` VARCHAR(255) NOT NULL,
        `data` mediumblob,
        `create_time` int(11),
        `update_time` int(11),
        `expire_time` int(11),
        PRIMARY KEY  (`id`),
        KEY `IDX_EXPIRE_TIME` (`expire_time`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('core/cache_tag')}` (
    `tag` VARCHAR(255) NOT NULL,
    `cache_id` VARCHAR(255) NOT NULL,
    KEY `IDX_TAG` (`tag`),
    KEY `IDX_CACHE_ID` (`cache_id`),
    CONSTRAINT `FK_CORE_CACHE_TAG` FOREIGN KEY (`cache_id`) REFERENCES `{$installer->getTable('core/cache')}` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `{$installer->getTable('core/cache_option')}` (
        `code` VARCHAR(32) NOT NULL,
        `value` tinyint(3),
        PRIMARY KEY  (`code`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->run("

ALTER TABLE `{$installer->getTable('core_store_group')}`
  ADD CONSTRAINT `FK_STORE_GROUP_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$installer->getTable('core_store')}`
  ADD CONSTRAINT `FK_STORE_GROUP_STORE` FOREIGN KEY (`group_id`) REFERENCES `{$installer->getTable('core_store_group')}` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_STORE_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$installer->getTable('core_layout_link')}`
  ADD CONSTRAINT `FK_CORE_LAYOUT_LINK_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CORE_LAYOUT_LINK_UPDATE` FOREIGN KEY (`layout_update_id`) REFERENCES `{$installer->getTable('core_layout_update')}` (`layout_update_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$installer->getTable('core_session')}`
  ADD CONSTRAINT `FK_SESSION_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$installer->getTable('core_website')}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$installer->getTable('core_translate')}`
  ADD CONSTRAINT `FK_CORE_TRANSLATE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$installer->getTable('core_url_rewrite')}`
  ADD CONSTRAINT `FK_CORE_URL_REWRITE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$installer->getTable('core_url_rewrite_tag')}`
  ADD CONSTRAINT `FK_CORE_URL_REWRITE_TAG_URL_REWRITE` FOREIGN KEY (`url_rewrite_id`) REFERENCES `{$installer->getTable('core_url_rewrite')}` (`url_rewrite_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$installer->getTable('design_change')}`
  ADD CONSTRAINT `FK_DESIGN_CHANGE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;

");

$installer->getConnection()->addColumn($this->getTable('core/url_rewrite'), 'entity_id', 'INT( 10 ) NOT NULL AFTER `store_id`');
$installer->run("UPDATE {$this->getTable('core/url_rewrite')} set `entity_id` = SUBSTR(`id_path`, LOCATE('/', `id_path`)+1, IF(LOCATE('/', `id_path`, LOCATE('/', `id_path`)+1) = 0, LENGTH(`id_path`) , LOCATE('/', `id_path`, LOCATE('/', `id_path`)+1)) - LOCATE('/', `id_path`)+1);");
$installer->run("CREATE INDEX entity_id ON {$this->getTable('core/url_rewrite')} (entity_id);");
$installer->run("UPDATE {$this->getTable('core/url_rewrite')} SET type = IF(id_path LIKE 'category/%', 1, IF(id_path LIKE 'product/%', 2, 3));");
$installer->run("
ALTER TABLE `{$installer->getTable('core_url_rewrite')}`
    DROP `entity_id`,
    DROP `type`,
    ADD `is_system` tinyint(1) unsigned default '1' AFTER `target_path`,
    DROP INDEX `store_id`,
    ADD INDEX `FK_CORE_URL_REWRITE_STORE` (`store_id`),
    DROP INDEX `id_path`,
    ADD UNIQUE `UNQ_PATH` (`store_id`, `id_path`, `is_system`),
    DROP INDEX `request_path`,
    ADD UNIQUE `UNQ_REQUEST_PATH` (`store_id`, `request_path`),
    DROP INDEX `target_path`,
    ADD INDEX `IDX_TARGET_PATH` (`store_id`, `target_path`);
DROP TABLE IF EXISTS `{$installer->getTable('core_url_rewrite_tag')}`;
");
$conn = $installer->getConnection();
$table = $this->getTable('design_change');

try {
    $conn->addColumn($table, 'design', "varchar(255) not null default ''");
} catch (Exception $e) {
}

$conn->dropColumn($table, 'package');
$conn->dropColumn($table, 'theme');
$installer->getConnection()->addColumn($installer->getTable('core_website'), 'is_default', 'tinyint(1) unsigned default 0');
$select = $installer->getConnection()->select()
    ->from($installer->getTable('core_website'))
    ->where('website_id > ?', 0)
    ->order('website_id')
    ->limit(1);
$row = $installer->getConnection()->fetchRow($select);

if ($row) {
    $whereBind = $installer->getConnection()->quoteInto('website_id=?', $row['website_id']);
    $installer->getConnection()->update($installer->getTable('core_website'),
        array('is_default' => 1),
        $whereBind
    );
}

$installer->run("

ALTER TABLE `{$this->getTable('design_change')}`
 CHANGE `date_from` `date_from` DATE NULL,
 CHANGE `date_to` `date_to` DATE NULL

");

$installer->run("
ALTER TABLE `{$this->getTable('core_url_rewrite')}` ADD INDEX `IDX_ID_PATH` ( `id_path` );
");

$installer->getConnection()->changeColumn(
    $this->getTable('core_session'), 'session_data', 'session_data', 'MEDIUMBLOB NOT NULL'
);

$installer->getConnection()->changeColumn(
    $installer->getTable('core_store'), 'name', 'name', 'varchar(255) not null', true
);

$installer->getConnection()->changeColumn(
    $installer->getTable('core_store_group'), 'name', 'name', 'varchar(255) not null', true
);

$installer->getConnection()->addConstraint('FK_CORE_URL_REWRITE_STORE',
    $installer->getTable('core/url_rewrite'), 'store_id',
    $installer->getTable('core/store'), 'store_id',
    'CASCADE', 'CASCADE', true);

$installer->getConnection()->addColumn($installer->getTable('core/layout_update'), 'sort_order', "smallint(5) NOT NULL DEFAULT '0'");
$installer->getConnection()->addColumn($installer->getTable('core/email_template'), 'orig_template_code', "VARCHAR(200) DEFAULT NULL");
$installer->getConnection()->addColumn(
    $installer->getTable('core_email_template'), 'template_styles', "text AFTER `template_text`"
);

$installer->getConnection()->addColumn($installer->getTable('core_email_variable'),
    'is_html', "tinyint(1) NOT NULL DEFAULT '0'");
$installer->getConnection()->changeColumn($installer->getTable('core_email_variable_value'),
    'value', 'value', 'TEXT NOT NULL');

$installer->run("
    ALTER TABLE `{$installer->getTable('core_email_variable')}` RENAME TO `{$installer->getTable('core/variable')}`;
    ALTER TABLE `{$installer->getTable('core_email_variable_value')}` RENAME TO `{$installer->getTable('core/variable_value')}`;
");

$installer->getConnection()->dropForeignKey($installer->getTable('core/variable_value'), 'FK_CORE_EMAIL_VARIABLE_VALUE_STORE_ID');
$installer->getConnection()->dropForeignKey($installer->getTable('core/variable_value'), 'FK_CORE_EMAIL_VARIABLE_VALUE_VARIABLE_ID');

$installer->getConnection()->addConstraint('FK_CORE_VARIABLE_VALUE_STORE_ID', $installer->getTable('core/variable_value'),
    'store_id', $installer->getTable('core/store'), 'store_id');
$installer->getConnection()->addConstraint('FK_CORE_VARIABLE_VALUE_VARIABLE_ID', $installer->getTable('core/variable_value'),
    'variable_id', $installer->getTable('core/variable'), 'variable_id');

$installer->getConnection()->addColumn($installer->getTable('core/variable_value'), 'plain_value', 'TEXT NOT NULL');
$installer->getConnection()->addColumn($installer->getTable('core/variable_value'), 'html_value', 'TEXT NOT NULL');

$select = $installer->getConnection()->select()
    ->from(array('main_table' => $installer->getTable('core/variable')), array())
    ->join(array('value_table' => $installer->getTable('core/variable_value')),
        'value_table.variable_id = main_table.variable_id', array())
    ->columns(array('main_table.variable_id', 'main_table.is_html', 'value_table.value'));

$data = array();
foreach ($installer->getConnection()->fetchAll($select) as $row) {
    if ($row['is_html']) {
        $value = array('html_value' => $row['value']);
    } else {
        $value = array('plain_value' => $row['value']);
    }
    $data[$row['variable_id']] = $value;
}

foreach ($data as $variableId => $value) {
    $installer->getConnection()->update($installer->getTable('core/variable_value'), $value,
        array('variable_id = ?' => $variableId));
}

$installer->getConnection()->dropColumn($installer->getTable('core/variable'), 'is_html');
$installer->getConnection()->dropColumn($installer->getTable('core/variable_value'), 'value');
$installer->getConnection()->addColumn($installer->getTable('core/resource'), 'data_version', 'varchar(50)');

$installer->getConnection()->addColumn($installer->getTable('core/layout_link'),
    'area', "VARCHAR(64) NOT NULL DEFAULT '' AFTER `store_id`");

$installer->getConnection()->update($installer->getTable('core/layout_link'),
     array('area' => Mage::getSingleton('core/design_package')->getArea()));

$installer->run("
ALTER TABLE `{$installer->getTable('core/url_rewrite')}`
  DROP INDEX `UNQ_PATH`,
  DROP INDEX `UNQ_REQUEST_PATH`,
  DROP INDEX `IDX_TARGET_PATH`,
  ADD UNIQUE `UNQ_PATH` (`id_path`, `is_system`, `store_id`),
  ADD UNIQUE `UNQ_REQUEST_PATH` (`request_path`, `store_id`),
  ADD INDEX `IDX_TARGET_PATH` (`target_path`, `store_id`);
");

$installer->getConnection()->modifyColumn(
    $installer->getTable('core_flag'), 'flag_id', 'INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT'
);

$installer->getConnection()->dropForeignKey($installer->getTable('core/cache_tag'), 'FK_CORE_CACHE_TAG');

$tagsTableName = $installer->getTable('core/cache_tag');
$installer->getConnection()->truncate($tagsTableName);
$installer->getConnection()->modifyColumn($tagsTableName, 'tag', 'VARCHAR(100)');
$installer->getConnection()->modifyColumn($tagsTableName, 'cache_id', 'VARCHAR(200)');
$installer->getConnection()->addKey($tagsTableName, '', array('tag', 'cache_id'), 'PRIMARY');
$installer->getConnection()->dropKey($tagsTableName, 'IDX_TAG');

$installer->endSetup();
