<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

// add average approved percent
$this->run("
ALTER TABLE `{$this->getTable('review_detail')}`
ADD COLUMN `reply_content` varchar(255) NULL DEFAULT '' AFTER `detail`;
");
$installer->endSetup();
