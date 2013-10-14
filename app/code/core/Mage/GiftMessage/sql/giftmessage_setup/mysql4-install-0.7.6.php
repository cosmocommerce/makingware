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
 * @package     Mage_GiftMessage
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('gift_message')};
CREATE TABLE {$this->getTable('gift_message')} (
    `gift_message_id` int(7) unsigned NOT NULL auto_increment,
    `customer_id` int(7) unsigned NOT NULL default '0',
    `sender` varchar(255) NOT NULL default '',
    `recipient` varchar(255) NOT NULL default '',
    `message` text NOT NULL,
    PRIMARY KEY  (`gift_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();

$installer->addAttribute('quote',              'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('quote_address',      'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('quote_item',         'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('quote_address_item', 'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('order',              'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('order_item',         'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('order_item',  'gift_message_available', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('catalog_product', 'gift_message_available', array(
        'backend'       => 'giftmessage/entity_attribute_backend_boolean_config',
        'frontend'      => '',
        'label'         => 'Allow Gift Message',
        'input'         => 'select',
        'class'         => '',
        'source'        => 'giftmessage/entity_attribute_source_boolean_config',
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,
        'default'       => '2',
        'visible_on_front' => false
    ));

/* @var $installer Mage_GiftMessage_Model_Mysql4_Setup */
$installer->updateAttribute('catalog_product', 'gift_message_available', 'is_configurable', 0);

$installer->addAttribute('quote',              'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('quote_address',      'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('quote_item',         'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('quote_address_item', 'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('order',              'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('order_item',         'gift_message_id', array('type' => 'int', 'visible' => false, 'required' => false));
$installer->addAttribute('order_item',  	   'gift_message_available', array('type' => 'int', 'visible' => false, 'required' => false));

/* $installer Mage_Core_Model_Resource_Setup */

$pathesForReplace = array(
    'sales/gift_messages/allow_order' => 'sales/gift_options/allow_order',
    'sales/gift_messages/allow_items' => 'sales/gift_options/allow_items'
);

foreach ($pathesForReplace as $from => $to) {
    $installer->run(sprintf("UPDATE `%s` SET `path` = '%s' WHERE `path` = '%s'",
        $this->getTable('core/config_data'), $to, $from
    ));
}

/*
 * Create new attribute group and move gift_message_available attribute to this group
 */
$entityTypeId = $installer->getEntityTypeId('catalog_product');
$attributeId  = $installer->getAttributeId('catalog_product', 'gift_message_available');

$attributeSets = $installer->_conn->fetchAll('select * from '.$this->getTable('eav/attribute_set').' where entity_type_id=?', $entityTypeId);
foreach ($attributeSets as $attributeSet) {
    $setId = $attributeSet['attribute_set_id'];
    $installer->addAttributeGroup($entityTypeId, $setId, 'Gift Options');
    $groupId = $installer->getAttributeGroupId($entityTypeId, $setId, 'Gift Options');
    $installer->addAttributeToGroup($entityTypeId, $setId, $groupId, $attributeId);
}

/* $installer Mage_Core_Model_Resource_Setup */

$installer->updateAttribute(
    'catalog_product',
    'gift_message_available',
    'source_model',
    'eav/entity_attribute_source_boolean'
);

$installer->updateAttribute(
    'catalog_product',
    'gift_message_available',
    'backend_model',
    'catalog/product_attribute_backend_boolean'
);

$installer->updateAttribute(
    'catalog_product',
    'gift_message_available',
    'frontend_input_renderer',
    'adminhtml/catalog_product_helper_form_config'
);

$installer->updateAttribute(
    'catalog_product',
    'gift_message_available',
    'default_value',
    ''
);

/*
 * Update previously saved data for 'gift_message_available' attribute
 */
$entityTypeId = $installer->getEntityTypeId('catalog_product');
$attributeId  = $installer->getAttributeId($entityTypeId, 'gift_message_available');

$installer->getConnection()->update($installer->getTable('catalog_product_entity_varchar'),
    array('value' => ''),
    array(
        'entity_type_id =?' => $entityTypeId,
        'attribute_id =?' => $attributeId,
        'value =?' => '2'
    )
);

/* $installer Mage_Core_Model_Resource_Setup */

$installer->updateAttribute('catalog_product', 'gift_message_available', 'apply_to', '');

/* $installer Mage_Core_Model_Resource_Setup */

$installer->updateAttribute(
    'catalog_product',
    'gift_message_available',
    'frontend_input_renderer',
    'giftmessage/adminhtml_product_helper_form_config'
);