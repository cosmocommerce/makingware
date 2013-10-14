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
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
CREATE TABLE `{$installer->getTable('checkout_agreement')}` (
  `agreement_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `content_height` varchar(25) DEFAULT NULL,
  `checkbox_text` text NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '0',
  `is_html` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`agreement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$this->getTable('checkout_agreement_store')}` (
 `agreement_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `agreement_id` (`agreement_id`,`store_id`),
  KEY `FK_CHECKOUT_AGREEMENT_STORE` (`store_id`),
  CONSTRAINT `FK_CHECKOUT_AGREEMENT` FOREIGN KEY (`agreement_id`) REFERENCES `checkout_agreement` (`agreement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CHECKOUT_AGREEMENT_STORE` FOREIGN KEY (`store_id`) REFERENCES `core_store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$setup = $installer->getConnection();

$select = $setup->select()
    ->from($installer->getTable('core/config_data'), 'COUNT(*)')
    ->where('path=?', 'customer/address/prefix_show')
    ->where('value!=?', '0');
$showPrefix = (bool)Mage::helper('customer/address')->getConfig('prefix_show')
    || ($setup->fetchOne($select) > 0);

$select = $setup->select()
    ->from($installer->getTable('core/config_data'), 'COUNT(*)')
    ->where('path=?', 'customer/address/suffix_show')
    ->where('value!=?', '0');
$showSuffix = (bool)Mage::helper('customer/address')->getConfig('suffix_show')
    || ($setup->fetchOne($select) > 0);

$select = $setup->select()
    ->from($installer->getTable('core/config_data'), 'COUNT(*)')
    ->where('path=?', 'customer/address/dob_show')
    ->where('value!=?', '0');
$showDob = (bool)Mage::helper('customer/address')->getConfig('dob_show')
    || ($setup->fetchOne($select) > 0);

$customerEntityTypeId = $installer->getEntityTypeId('customer');
$addressEntityTypeId  = $installer->getEntityTypeId('customer_address');

/**
 *****************************************************************************
 * checkout/onepage/register
 *****************************************************************************
 */

$setup->insert($installer->getTable('eav/form_type'), array(
    'code'      => 'checkout_onepage_register',
    'label'     => 'checkout_onepage_register',
    'is_system' => 1,
    'theme'     => '',
    'store_id'  => 0
));
$formTypeId   = $setup->lastInsertId();

$setup->insert($installer->getTable('eav/form_type_entity'), array(
    'type_id'        => $formTypeId,
    'entity_type_id' => $customerEntityTypeId
));
$setup->insert($installer->getTable('eav/form_type_entity'), array(
    'type_id'        => $formTypeId,
    'entity_type_id' => $addressEntityTypeId
));

$elementSort = 0;
if ($showPrefix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'prefix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'name'),
    'sort_order'    => $elementSort++
));
if ($showSuffix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'suffix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'company'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'email'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'street'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'city'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'region'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'postcode'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'country_id'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'telephone'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'fax'),
    'sort_order'    => $elementSort++
));
if ($showDob) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'dob'),
        'sort_order'    => $elementSort++
    ));
}

/**
 *****************************************************************************
 * checkout/onepage/register_guest
 *****************************************************************************
 */

$setup->insert($installer->getTable('eav/form_type'), array(
    'code'      => 'checkout_onepage_register_guest',
    'label'     => 'checkout_onepage_register_guest',
    'is_system' => 1,
    'theme'     => '',
    'store_id'  => 0
));
$formTypeId   = $setup->lastInsertId();

$setup->insert($installer->getTable('eav/form_type_entity'), array(
    'type_id'        => $formTypeId,
    'entity_type_id' => $customerEntityTypeId
));
$setup->insert($installer->getTable('eav/form_type_entity'), array(
    'type_id'        => $formTypeId,
    'entity_type_id' => $addressEntityTypeId
));

$elementSort = 0;
if ($showPrefix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'prefix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'name'),
    'sort_order'    => $elementSort++
));
if ($showSuffix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'suffix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'company'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'email'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'street'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'city'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'region'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'postcode'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'country_id'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'telephone'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'fax'),
    'sort_order'    => $elementSort++
));
if ($showDob) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'dob'),
        'sort_order'    => $elementSort++
    ));
}

/**
 *****************************************************************************
 * checkout/onepage/shipping_address
 *****************************************************************************
 */

$setup->insert($installer->getTable('eav/form_type'), array(
    'code'      => 'checkout_onepage_shipping_address',
    'label'     => 'checkout_onepage_shipping_address',
    'is_system' => 1,
    'theme'     => '',
    'store_id'  => 0
));
$formTypeId   = $setup->lastInsertId();

$setup->insert($installer->getTable('eav/form_type_entity'), array(
    'type_id'        => $formTypeId,
    'entity_type_id' => $addressEntityTypeId
));

$elementSort = 0;
if ($showPrefix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'prefix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'name'),
    'sort_order'    => $elementSort++
));
if ($showSuffix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => null,
        'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'suffix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'company'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'street'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'city'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'region'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'postcode'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'country_id'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'telephone'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => null,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'fax'),
    'sort_order'    => $elementSort++
));

/**
 *****************************************************************************
 * checkout/multishipping/register/
 *****************************************************************************
 */

$setup->insert($installer->getTable('eav/form_type'), array(
    'code'      => 'checkout_multishipping_register',
    'label'     => 'checkout_multishipping_register',
    'is_system' => 1,
    'theme'     => '',
    'store_id'  => 0
));
$formTypeId   = $setup->lastInsertId();

$setup->insert($installer->getTable('eav/form_type_entity'), array(
    'type_id'        => $formTypeId,
    'entity_type_id' => $customerEntityTypeId
));
$setup->insert($installer->getTable('eav/form_type_entity'), array(
    'type_id'        => $formTypeId,
    'entity_type_id' => $addressEntityTypeId
));

$setup->insert($installer->getTable('eav/form_fieldset'), array(
    'type_id'    => $formTypeId,
    'code'       => 'general',
    'sort_order' => 1
));
$fieldsetId = $setup->lastInsertId();

$setup->insert($installer->getTable('eav/form_fieldset_label'), array(
    'fieldset_id' => $fieldsetId,
    'store_id'    => 0,
    'label'       => 'Personal Information'
));

$elementSort = 0;
if ($showPrefix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => $fieldsetId,
        'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'prefix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'name'),
    'sort_order'    => $elementSort++
));
if ($showSuffix) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => $fieldsetId,
        'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'suffix'),
        'sort_order'    => $elementSort++
    ));
}
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'email'),
    'sort_order'    => $elementSort++
));
if ($showDob) {
    $setup->insert($installer->getTable('eav/form_element'), array(
        'type_id'       => $formTypeId,
        'fieldset_id'   => $fieldsetId,
        'attribute_id'  => $installer->getAttributeId($customerEntityTypeId, 'dob'),
        'sort_order'    => $elementSort++
    ));
}

$setup->insert($installer->getTable('eav/form_fieldset'), array(
    'type_id'    => $formTypeId,
    'code'       => 'address',
    'sort_order' => 2
));
$fieldsetId = $setup->lastInsertId();

$setup->insert($installer->getTable('eav/form_fieldset_label'), array(
    'fieldset_id' => $fieldsetId,
    'store_id'    => 0,
    'label'       => 'Address Information'
));

$elementSort = 0;
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'company'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'telephone'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'street'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'city'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'region'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'postcode'),
    'sort_order'    => $elementSort++
));
$setup->insert($installer->getTable('eav/form_element'), array(
    'type_id'       => $formTypeId,
    'fieldset_id'   => $fieldsetId,
    'attribute_id'  => $installer->getAttributeId($addressEntityTypeId, 'country_id'),
    'sort_order'    => $elementSort++
));

$connection = $installer->getConnection();
$table = $installer->getTable('core/config_data');

$select = $connection->select()
    ->from($table, array('config_id', 'value'))
    ->where('path = ?', 'checkout/options/onepage_checkout_disabled');

$data = $connection->fetchAll($select);

if ($data) {
    try {
        $connection->beginTransaction();

        foreach ($data as $value) {
            $bind = array(
                'path'  => 'checkout/options/onepage_checkout_enabled',
                'value' => !((bool)$value['value'])
            );
            $where = 'config_id = ' . $value['config_id'];
            $connection->update($table, $bind, $where);
        }

        $connection->commit();
    } catch (Exception $e) {
        $installer->getConnection()->rollback();
        throw $e;
    }
}

$installer->endSetup();
