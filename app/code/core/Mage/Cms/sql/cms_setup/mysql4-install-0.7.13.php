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
 * @package     Mage_Cms
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('cms_block')};
CREATE TABLE {$this->getTable('cms_block')} (
  `block_id` smallint(6) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `identifier` varchar(255) NOT NULL default '',
  `content` text,
  `creation_time` datetime default NULL,
  `update_time` datetime default NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `store_id` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS Blocks';

insert into {$this->getTable('cms_block')}(`block_id`,`title`,`identifier`,`content`,`creation_time`,`update_time`,`is_active`,`store_id`) values (5,'Footer Links','footer_links','<ul>\r\n<li><a href=\"{{store direct_url=\"about-magento-demo-store\"}}\">About Us</a></li>\r\n<li class=\"last\"><a href=\"{{store direct_url=\"customer-service\"}}\">Customer Service</a></li>\r\n</ul>',NOW(),NOW(),1,0);

-- DROP TABLE IF EXISTS {$this->getTable('cms_page')};
CREATE TABLE {$this->getTable('cms_page')} (
  `page_id` smallint(6) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `root_template` varchar(255) NOT NULL default '',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `identifier` varchar(100) NOT NULL default '',
  `content` text,
  `creation_time` datetime default NULL,
  `update_time` datetime default NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `store_id` tinyint(4) NOT NULL default '1',
  `sort_order` tinyint(4) NOT NULL default '0',
  `layout_update_xml` text,
  PRIMARY KEY  (`page_id`),
  UNIQUE KEY `identifier` (`identifier`,`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS pages';

insert into {$this->getTable('cms_page')}(`page_id`,`title`,`root_template`,`meta_keywords`,`meta_description`,`identifier`,`content`,`creation_time`,`update_time`,`is_active`,`store_id`,`sort_order`) 
values 
(1,'找不到页面(404)','right_column','Page keywords','Page description','no-route','<div class=\"page-title\">\r\n<h1>很抱歉，您的网址输入有误，请检查拼写后再尝试...</h1>\r\n</div>\r\n<dl> <dt>你打开的页面无法找到，你是不是输入错误啦？</dt> <dd> \r\n<a href=\"{{store url=\"\"}}\">首页</a> <span class=\"separator\">|</span> <a href=\"{{store url=\"customer/account\"}}\">账户中心</a>\r\n</dd> \r\n</dl>','2007-06-20 18:38:32','2007-08-26 19:11:13',1,0,0),
(2,'首页','right_column','','','home','<div class=\"page-title\"><h2>Home Page</h2></div>\r\n','2007-08-23 10:03:25','2007-09-06 13:26:53',1,0,0),
(3,'关于我们','one_column','','','about-magento-demo-store','<p style=\"padding:36px; line-height:40px;\">麦金电商是一家年轻且富有活力的公司。<br /> 我们的团队由资深电子商务运营顾问，网络营销专才，高级软件架构师以及资深美术设计师组成。<br /> 我们坚持站在客户的角度为客户设计方案，坚持双赢的商业原则，坚持两个凡是的经营理念\"<br /> 凡是我们做不好的，我们不做；<br />凡是我们能做的，我们都做到最好！\"。</p>','2007-08-30 14:01:18','2007-08-30 14:01:18',1,0,0),
(4,'帮助中心','help_columns','','','help','<p style=\"text-align:center; margin:50px auto;\"><img src=\"{{media url=\"/help/helpsort_lc.gif\"}}\" alt=\"帮助中心\" /></p>','2007-08-30 14:02:20','2007-08-30 14:03:37',1,0,0);

ALTER TABLE {$this->getTable('cms_block')}
    CHANGE `store_id` `store_id` smallint(5) unsigned NULL DEFAULT '0';
ALTER TABLE {$this->getTable('cms_block')}
    ADD CONSTRAINT `FK_CMS_BLOCK_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL;

ALTER TABLE {$this->getTable('cms_page')}
    CHANGE `store_id` `store_id` smallint(5) unsigned NULL DEFAULT '0';
ALTER TABLE {$this->getTable('cms_page')}
    ADD CONSTRAINT `FK_CMS_PAGE_STORE` FOREIGN KEY (`store_id`)
    REFERENCES {$this->getTable('core_store')} (`store_id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL;
UPDATE `{$this->getTable('cms_page')}` SET `root_template` = 'two_columns_left' WHERE `root_template` LIKE 'left_column';
UPDATE `{$this->getTable('cms_page')}` SET `root_template` = 'two_columns_right' WHERE `root_template` LIKE 'right_column';
UPDATE `{$this->getTable('cms_page')}` SET `root_template` = 'three_columns' WHERE `root_template` LIKE 'three_column';

DROP TABLE IF EXISTS `{$this->getTable('cms/page_store')}`;
CREATE TABLE `{$this->getTable('cms/page_store')}` (
  `page_id` smallint(6) NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`page_id`,`store_id`),
  CONSTRAINT `FK_CMS_PAGE_STORE_PAGE` FOREIGN KEY (`page_id`) REFERENCES `{$this->getTable('cms/page')}` (`page_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_CMS_PAGE_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS Pages to Stores';

INSERT INTO {$this->getTable('cms/page_store')} (`page_id`, `store_id`) SELECT `page_id`, `store_id` FROM {$this->getTable('cms/page')};

DROP TABLE IF EXISTS {$this->getTable('cms/block_store')};
CREATE TABLE {$this->getTable('cms/block_store')} (
  `block_id` smallint(6) NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`block_id`,`store_id`),
  CONSTRAINT `FK_CMS_BLOCK_STORE_BLOCK` FOREIGN KEY (`block_id`) REFERENCES {$this->getTable('cms/block')} (`block_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_CMS_BLOCK_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS Blocks to Stores';

INSERT INTO {$this->getTable('cms/block_store')} (`block_id`, `store_id`) SELECT `block_id`, `store_id` FROM {$this->getTable('cms/block')};


    ");
$conn = $installer->getConnection();
$table = $installer->getTable('cms_page');

$conn->addColumn($table, 'custom_theme', 'varchar(100)');
$conn->addColumn($table, 'custom_theme_from', 'date');
$conn->addColumn($table, 'custom_theme_to', 'date');

$installer->getConnection()->dropKey($this->getTable('cms/page'), 'identifier');

$installer->run("ALTER TABLE `{$this->getTable('cms/page')}` ADD KEY `identifier` (`identifier`)");

$installer->getConnection()->dropColumn($this->getTable('cms/page'), 'store_id');

$installer->getConnection()->dropColumn($this->getTable('cms/block'), 'store_id');
$connection = $installer->getConnection();
$connection->insert($installer->getTable('cms/page'), array(
    'title'             => '请启用Cookies',
    'root_template'     => 'one_column',
    'identifier'        => 'enable-cookies',
    'content'           => "<div class=\"std\">\r\n<ul class=\"messages\">\r\n<li class=\"notice-msg\"> \r\n<ul>\r\n<li>请打开您的浏览器的cookies功能，以便继续下面的操作。</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n<div class=\"page-title\">\r\n<h1><a name=\"top\"></a>什么是 Cookies?</h1>\r\n</div>\r\n<p>Cookie（复数形态Cookies），中文名称为小型文字档案或小甜饼，指某些网站为了辨别用户身份而储存在用户本地终端（Client Side）上的数据（通常经过加密）。定义于RFC2109。它是网景公司的前雇员Lou Montulli在1993年3月的发明。 在您访问我们我们网站时，Cookies 能自动识别，以便我们能够根据您的个性化，为您提供更好的服务。  我们还使用Cookie （和类似的浏览器的数据，如Flash Cookie）来防止欺诈或其他用途。 如果您的网页浏览器设置为拒绝来自我们网站的Cookies，您将无法完成购买或使用我们网站的某些功能，比如在购物车中储存物品或接收个性化的建议。 因此，我们强烈建议您配置您的网页浏览器以接受来自我们网站的Cookies。</p>\r\n<h2 class=\"subtitle\">配置Cookies的方法</h2>\r\n<ul class=\"disc\">\r\n<li><a href=\"#ie7\">Internet Explorer 7.x</a></li>\r\n<li><a href=\"#ie6\">Internet Explorer 6.x</a></li>\r\n<li><a href=\"#firefox\">Mozilla/Firefox</a></li>\r\n<li><a href=\"#opera\">Opera 7.x</a></li>\r\n</ul>\r\n<h3><a name=\"ie7\"></a>Internet Explorer 7.x</h3>\r\n<ol>\r\n<li>\r\n<p>启动 IE</p>\r\n</li>\r\n<li>\r\n<p><span style=\"color: #000000; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;\">在<span class=\"Apple-converted-space\">&nbsp;</span></span><strong style=\"margin: 0px; padding: 0px; color: #000000; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;\">工具</strong><span style=\"color: #000000; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;\"><span class=\"Apple-converted-space\">&nbsp;</span>菜单下, 点击<span class=\"Apple-converted-space\">&nbsp;</span></span><strong style=\"margin: 0px; padding: 0px; color: #000000; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 18px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;\">Internet Options</strong></p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie7-1.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Click the <strong>Privacy</strong> tab</p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie7-2.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Click the <strong>Advanced</strong> button</p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie7-3.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Put a check mark in the box for <strong>Override Automatic Cookie Handling</strong>, put another check mark in the <strong>Always accept session cookies </strong>box</p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie7-4.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Click <strong>OK</strong></p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie7-5.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Click <strong>OK</strong></p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie7-6.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Restart Internet Explore</p>\r\n</li>\r\n</ol>\r\n<p class=\"a-top\"><a href=\"#top\">Back to Top</a></p>\r\n<h3><a name=\"ie6\"></a>Internet Explorer 6.x</h3>\r\n<ol>\r\n<li>\r\n<p>Select <strong>Internet Options</strong> from the Tools menu</p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie6-1.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Click on the <strong>Privacy</strong> tab</p>\r\n</li>\r\n<li>\r\n<p>Click the <strong>Default</strong> button (or manually slide the bar down to <strong>Medium</strong>) under <strong>Settings</strong>. Click <strong>OK</strong></p>\r\n<p><img src=\"{{skin url=\"images/cookies/ie6-2.gif\"}}\" alt=\"\" /></p>\r\n</li>\r\n</ol>\r\n<p class=\"a-top\"><a href=\"#top\">Back to Top</a></p>\r\n<h3><a name=\"firefox\"></a>Mozilla/Firefox</h3>\r\n<ol>\r\n<li>\r\n<p>Click on the <strong>Tools</strong>-menu in Mozilla</p>\r\n</li>\r\n<li>\r\n<p>Click on the <strong>Options...</strong> item in the menu - a new window open</p>\r\n</li>\r\n<li>\r\n<p>Click on the <strong>Privacy</strong> selection in the left part of the window. (See image below)</p>\r\n<p><img src=\"{{skin url=\"images/cookies/firefox.png\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>Expand the <strong>Cookies</strong> section</p>\r\n</li>\r\n<li>\r\n<p>Check the <strong>Enable cookies</strong> and <strong>Accept cookies normally</strong> checkboxes</p>\r\n</li>\r\n<li>\r\n<p>Save changes by clicking <strong>Ok</strong>.</p>\r\n</li>\r\n</ol>\r\n<p class=\"a-top\"><a href=\"#top\">Back to Top</a></p>\r\n<h3><a name=\"opera\"></a>Opera 7.x</h3>\r\n<ol>\r\n<li>\r\n<p>Click on the <strong>Tools</strong> menu in Opera</p>\r\n</li>\r\n<li>\r\n<p>Click on the <strong>Preferences...</strong> item in the menu - a new window open</p>\r\n</li>\r\n<li>\r\n<p>Click on the <strong>Privacy</strong> selection near the bottom left of the window. (See image below)</p>\r\n<p><img src=\"{{skin url=\"images/cookies/opera.png\"}}\" alt=\"\" /></p>\r\n</li>\r\n<li>\r\n<p>The <strong>Enable cookies</strong> checkbox must be checked, and <strong>Accept all cookies</strong> should be selected in the \"<strong>Normal cookies</strong>\" drop-down</p>\r\n</li>\r\n<li>\r\n<p>Save changes by clicking <strong>Ok</strong></p>\r\n</li>\r\n</ol>\r\n<p class=\"a-top\"><a href=\"#top\">Back to Top</a></p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 0
));

$table = $installer->getTable('cms_widget');

$installer->run('
CREATE TABLE IF NOT EXISTS `' . $table . '` (
  `widget_id` int(10) unsigned NOT NULL auto_increment,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `parameters` text,
  PRIMARY KEY  (`widget_id`),
  KEY `IDX_CODE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT="CMS Preconfigured Widgets";');

$pageTable = $installer->getTable('cms/page');
$blockTable = $installer->getTable('cms/block');

$installer->getConnection()->modifyColumn($pageTable, 'content', 'MEDIUMTEXT');
$installer->getConnection()->modifyColumn($blockTable, 'content', 'MEDIUMTEXT');

$pageTable = $installer->getTable('cms/page');

$installer->getConnection()->addColumn($pageTable, 'custom_root_template',
    "VARCHAR(255) NOT NULL DEFAULT '' AFTER `custom_theme`");

$installer->getConnection()->addColumn($pageTable, 'custom_layout_update_xml',
    'TEXT NULL AFTER `custom_root_template`');

if ($installer->getTable('cms_widget')) {
    $installer->run("
        ALTER TABLE `{$installer->getTable('cms_widget')}` COMMENT 'Preconfigured Widgets';
        ALTER TABLE `{$installer->getTable('cms_widget')}` RENAME TO `{$installer->getTable('widget/widget')}`;
    ");
}

$installer->getConnection()->addColumn($installer->getTable('cms/page'), 'content_heading',
    "VARCHAR(255) NOT NULL DEFAULT '' AFTER `identifier`");


$installer->endSetup();
