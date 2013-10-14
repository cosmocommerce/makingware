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
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Sales_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
/* Orders */
CREATE TABLE `{$installer->getTable('sales_flat_order')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `state` varchar(32) default NULL,
    `status` varchar(32) default NULL,
    `coupon_code` varchar(255) default NULL,
    `protect_code` varchar(255) default NULL,
    `shipping_description` varchar(255) default NULL,
    `is_virtual` tinyint(1) unsigned default NULL,
    `store_id` smallint(5) unsigned default NULL,
    `customer_id` int(10) unsigned default NULL,
    `base_discount_amount` decimal(12,4) default NULL,
    `base_discount_canceled` decimal(12,4) default NULL,
    `base_discount_invoiced` decimal(12,4) default NULL,
    `base_discount_refunded` decimal(12,4) default NULL,
    `base_grand_total` decimal(12,4) default NULL,
    `base_shipping_amount` decimal(12,4) default NULL,
    `base_shipping_canceled` decimal(12,4) default NULL,
    `base_shipping_invoiced` decimal(12,4) default NULL,
    `base_shipping_refunded` decimal(12,4) default NULL,
    `base_subtotal` decimal(12,4) default NULL,
    `base_subtotal_canceled` decimal(12,4) default NULL,
    `base_subtotal_invoiced` decimal(12,4) default NULL,
    `base_subtotal_refunded` decimal(12,4) default NULL,
    `base_to_global_rate` decimal(12,4) default NULL,
    `base_to_order_rate` decimal(12,4) default NULL,
    `base_total_canceled` decimal(12,4) default NULL,
    `base_total_invoiced` decimal(12,4) default NULL,
    `base_total_invoiced_cost` decimal(12,4) default NULL,
    `base_total_offline_refunded` decimal(12,4) default NULL,
    `base_total_online_refunded` decimal(12,4) default NULL,
    `base_total_paid` decimal(12,4) default NULL,
    `base_total_qty_ordered` decimal(12,4) default NULL,
    `base_total_refunded` decimal(12,4) default NULL,
    `discount_amount` decimal(12,4) default NULL,
    `discount_canceled` decimal(12,4) default NULL,
    `discount_invoiced` decimal(12,4) default NULL,
    `discount_refunded` decimal(12,4) default NULL,
    `grand_total` decimal(12,4) default NULL,
    `shipping_amount` decimal(12,4) default NULL,
    `shipping_canceled` decimal(12,4) default NULL,
    `shipping_invoiced` decimal(12,4) default NULL,
    `shipping_refunded` decimal(12,4) default NULL,
    `store_to_base_rate` decimal(12,4) default NULL,
    `store_to_order_rate` decimal(12,4) default NULL,
    `subtotal` decimal(12,4) default NULL,
    `subtotal_canceled` decimal(12,4) default NULL,
    `subtotal_invoiced` decimal(12,4) default NULL,
    `subtotal_refunded` decimal(12,4) default NULL,
    `total_canceled` decimal(12,4) default NULL,
    `total_invoiced` decimal(12,4) default NULL,
    `total_offline_refunded` decimal(12,4) default NULL,
    `total_online_refunded` decimal(12,4) default NULL,
    `total_paid` decimal(12,4) default NULL,
    `total_qty_ordered` decimal(12,4) default NULL,
    `total_refunded` decimal(12,4) default NULL,
    `can_ship_partially` tinyint(1) unsigned default NULL,
    `can_ship_partially_item` tinyint(1) unsigned default NULL,
    `customer_is_guest` tinyint(1) unsigned default NULL,
    `customer_note_notify` tinyint(1) unsigned default NULL,
    `customer_group_id` smallint(5) default NULL,
    `edit_increment` int(10) default NULL,
    `email_sent` tinyint(1) unsigned default NULL,
    `forced_do_shipment_with_invoice` tinyint(1) unsigned default NULL,
    `gift_message_id` int(10) default NULL,
    `payment_authorization_expiration` int(10) default NULL,
    `quote_address_id` int(10) default NULL,
    `quote_id` int(10) default NULL,
    `shipping_address_id` int(10) default NULL,
    `adjustment_negative` decimal(12,4) default NULL,
    `adjustment_positive` decimal(12,4) default NULL,
    `base_adjustment_negative` decimal(12,4) default NULL,
    `base_adjustment_positive` decimal(12,4) default NULL,
    `base_shipping_discount_amount` decimal(12,4) default NULL,
    `base_total_due` decimal(12,4) default NULL,
    `payment_authorization_amount` decimal(12,4) default NULL,
    `shipping_discount_amount` decimal(12,4) default NULL,
    `total_due` decimal(12,4) default NULL,
    `weight` decimal(12,4) default NULL,
    `customer_dob` datetime default NULL,
    `increment_id` varchar(50) default NULL,
    `applied_rule_ids` varchar(255) default NULL,
    `base_currency_code` char(3) default NULL,
    `customer_email` varchar(255) default NULL,
    `customer_name` varchar(255) default NULL,
    `customer_prefix` varchar(255) default NULL,
    `customer_suffix` varchar(255) default NULL,
    `discount_description` varchar(255) default NULL,
    `ext_customer_id` varchar(255) default NULL,
    `ext_order_id` varchar(255) default NULL,
    `global_currency_code` char(3) default NULL,
    `hold_before_state` varchar(255) default NULL,
    `hold_before_status` varchar(255) default NULL,
    `order_currency_code` varchar(255) default NULL,
    `original_increment_id` varchar(50) default NULL,
    `relation_child_id` varchar(32) default NULL,
    `relation_child_real_id` varchar(32) default NULL,
    `relation_parent_id` varchar(32) default NULL,
    `relation_parent_real_id` varchar(32) default NULL,
    `remote_ip` varchar(255) default NULL,
    `shipping_method` varchar(255) default NULL,
    `store_currency_code` char(3) default NULL,
    `store_name` varchar(255) default NULL,
    `x_forwarded_for` varchar(255) default NULL,
    `customer_comment` text,
    `customer_note` text,
    `created_at` datetime default NULL,
    `updated_at` datetime default NULL,
    `total_item_count` smallint(5) unsigned DEFAULT '0',
    `customer_gender` int(11) DEFAULT NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STATUS` (`status`),
    KEY `IDX_STATE` (`state`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_CUSTOMER_ID` (`customer_id`),
    KEY `IDX_EXT_ORDER_ID` (`ext_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Orders Grid */
CREATE TABLE `{$installer->getTable('sales_flat_order_grid')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `status` varchar(32) default NULL,
    `store_id` smallint(5) unsigned default NULL,
    `customer_id` int(10) unsigned default NULL,
    `base_grand_total` decimal(12,4) default NULL,
    `base_total_paid` decimal(12,4) default NULL,
    `grand_total` decimal(12,4) default NULL,
    `total_paid` decimal(12,4) default NULL,
    `increment_id` varchar(50) default NULL,
    `base_currency_code` char(3) default NULL,
    `order_currency_code` varchar(255) default NULL,
    `shipping_name` varchar(255) default NULL,
    `created_at` datetime default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STATUS` (`status`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_BASE_GRAND_TOTAL` (`base_grand_total`),
    KEY `IDX_BASE_TOTAL_PAID` (`base_total_paid`),
    KEY `IDX_GRAND_TOTAL` (`grand_total`),
    KEY `IDX_TOTAL_PAID` (`total_paid`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_SHIPPING_NAME` (`shipping_name`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_CUSTOMER_ID` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Order Address */
CREATE TABLE `{$installer->getTable('sales_flat_order_address')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned default NULL,
    `customer_address_id` int(10) default NULL,
    `quote_address_id` int(10) default NULL,
    `customer_id` int(10) default NULL,
    `fax` varchar(255) default NULL,
    `postcode` varchar(255) default NULL,
    `street` varchar(255) default NULL,
    `area_id` int(10) unsigned DEFAULT NULL,
    `area` varchar(255) default NULL,
    `city_id` int(10) unsigned DEFAULT NULL,
    `city` varchar(255) default NULL,
    `region` varchar(255) default NULL,
    `region_id` int(10) unsigned DEFAULT NULL,
    `country_id` varchar(255) default NULL,
    `email` varchar(255) default NULL,
    `telephone` varchar(255) default NULL,
    `name` varchar(255) default NULL,
    `prefix` varchar(255) default NULL,
    `suffix` varchar(255) default NULL,
    `company` varchar(255) default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Order Comments */
CREATE TABLE `{$installer->getTable('sales_flat_order_status_history')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `is_customer_notified` int(10) default NULL,
    `comment` text,
    `status` varchar(32) default NULL,
    `created_at` datetime default NULL,
    `user_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`entity_id`),
    KEY `IDX_PARENT_ID` (`parent_id`),
    KEY `IDX_CREATED_AT` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Order Items */

CREATE TABLE `{$installer->getTable('sales_flat_order_item')}` (
    `item_id` int(10) unsigned NOT NULL auto_increment,
    `order_id` int(10) unsigned NOT NULL default '0',
    `parent_item_id` int(10) unsigned default NULL,
    `quote_item_id` int(10) unsigned default NULL,
    `store_id` smallint(5) unsigned default NULL,
    `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `product_id` int(10) unsigned default NULL,
    `product_type` varchar(255) default NULL,
    `product_options` text,
    `weight` decimal(12,4) default '0.0000',
    `is_virtual` tinyint(1) unsigned default NULL,
    `sku` varchar(255) NOT NULL default '',
    `name` varchar(255) default NULL,
    `description` text,
    `applied_rule_ids` text,
    `additional_data` text,
    `free_shipping` tinyint(1) unsigned NOT NULL default '0',
    `is_qty_decimal` tinyint(1) unsigned default NULL,
    `no_discount` tinyint(1) unsigned default '0',
    `qty_backordered` decimal(12,4) default '0.0000',
    `qty_canceled` decimal(12,4) default '0.0000',
    `qty_invoiced` decimal(12,4) default '0.0000',
    `qty_ordered` decimal(12,4) default '0.0000',
    `qty_refunded` decimal(12,4) default '0.0000',
    `qty_shipped` decimal(12,4) default '0.0000',
    `base_cost` decimal(12,4) default '0.0000',
    `price` decimal(12,4) NOT NULL default '0.0000',
    `base_price` decimal(12,4) NOT NULL default '0.0000',
    `original_price` decimal(12,4) default NULL,
    `base_original_price` decimal(12,4) default NULL,
    `discount_percent` decimal(12,4) default '0.0000',
    `discount_amount` decimal(12,4) default '0.0000',
    `base_discount_amount` decimal(12,4) default '0.0000',
    `discount_invoiced` decimal(12,4) default '0.0000',
    `base_discount_invoiced` decimal(12,4) default '0.0000',
    `amount_refunded` decimal(12,4) default '0.0000',
    `base_amount_refunded` decimal(12,4) default '0.0000',
    `row_total` decimal(12,4) NOT NULL default '0.0000',
    `base_row_total` decimal(12,4) NOT NULL default '0.0000',
    `row_invoiced` decimal(12,4) NOT NULL default '0.0000',
    `base_row_invoiced` decimal(12,4) NOT NULL default '0.0000',
    `row_weight` decimal(12,4) default '0.0000',
    `gift_message_id` int(10) default NULL,
    `gift_message_available` int(10) default NULL,
    `ext_order_item_id` varchar(255) default NULL,
    `locked_do_invoice` tinyint(1) unsigned default NULL,
    `locked_do_ship` tinyint(1) unsigned default NULL,
    PRIMARY KEY (`item_id`),
    KEY `IDX_ORDER` (`order_id`),
    KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Order Payment */

CREATE TABLE `{$installer->getTable('sales_flat_order_payment')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `base_shipping_captured` decimal(12,4) default NULL,
    `shipping_captured` decimal(12,4) default NULL,
    `amount_refunded` decimal(12,4) default NULL,
    `base_amount_paid` decimal(12,4) default NULL,
    `amount_canceled` decimal(12,4) default NULL,
    `base_amount_authorized` decimal(12,4) default NULL,
    `base_amount_paid_online` decimal(12,4) default NULL,
    `base_amount_refunded_online` decimal(12,4) default NULL,
    `base_shipping_amount` decimal(12,4) default NULL,
    `shipping_amount` decimal(12,4) default NULL,
    `amount_paid` decimal(12,4) default NULL,
    `amount_authorized` decimal(12,4) default NULL,
    `base_amount_ordered` decimal(12,4) default NULL,
    `base_shipping_refunded` decimal(12,4) default NULL,
    `shipping_refunded` decimal(12,4) default NULL,
    `base_amount_refunded` decimal(12,4) default NULL,
    `amount_ordered` decimal(12,4) default NULL,
    `base_amount_canceled` decimal(12,4) default NULL,
    `ideal_transaction_checked` tinyint(1) unsigned default NULL,
    `quote_payment_id` int(10) default NULL,
    `additional_data` text,
    `echeck_bank_name` varchar(255) default NULL,
    `method` varchar(255) default NULL,
    `cybersource_token` varchar(255) default NULL,
    `ideal_issuer_title` varchar(255) default NULL,
    `protection_eligibility` varchar(255) default NULL,
    `echeck_type` varchar(255) default NULL,
    `paybox_question_number` varchar(255) default NULL,
    `echeck_account_type` varchar(255) default NULL,
    `last_trans_id` varchar(255) default NULL,
    `cod_type` varchar(255) default NULL,
    `ideal_issuer_id` varchar(255) default NULL,
    `po_number` varchar(255) default NULL,
    `echeck_routing_number` varchar(255) default NULL,
    `account_status` varchar(255) default NULL,
    `anet_trans_method` varchar(255) default NULL,
    `echeck_account_name` varchar(255) default NULL,
    `flo2cash_account_id` varchar(255) default NULL,
    `paybox_request_number` varchar(255) default NULL,
    `address_status` varchar(255) default NULL,
    `additional_information` text,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Shipments */

CREATE TABLE `{$installer->getTable('sales_flat_shipment')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `store_id` smallint(5) unsigned default NULL,
    `total_weight` decimal(12,4) default NULL,
    `total_qty` decimal(12,4) default NULL,
    `email_sent` tinyint(1) unsigned default NULL,
    `order_id` int(10) unsigned NOT NULL,
    `customer_id` int(10) default NULL,
    `shipping_address_id` int(10) default NULL,
    `shipment_status` int(10) default NULL,
    `increment_id` varchar(50) default NULL,
    `created_at` datetime default NULL,
    `updated_at` datetime default NULL,
    `user_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_TOTAL_QTY` (`total_qty`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_ORDER_ID` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Shipments Grid */

CREATE TABLE `{$installer->getTable('sales_flat_shipment_grid')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `store_id` smallint(5) unsigned default NULL,
    `total_qty` decimal(12,4) default NULL,
    `order_id` int(10) unsigned NOT NULL,
    `shipment_status` int(10) default NULL,
    `increment_id` varchar(50) default NULL,
    `order_increment_id` varchar(50) default NULL,
    `created_at` datetime default NULL,
    `order_created_at` datetime default NULL,
    `shipping_name` varchar(255) default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_TOTAL_QTY` (`total_qty`),
    KEY `IDX_ORDER_ID` (`order_id`),
    KEY `IDX_SHIPMENT_STATUS` (`shipment_status`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_ORDER_INCREMENT_ID` (`order_increment_id`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_ORDER_CREATED_AT` (`order_created_at`),
    KEY `IDX_SHIPPING_NAME` (`shipping_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Shipment Items */

CREATE TABLE `{$installer->getTable('sales_flat_shipment_item')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `row_total` decimal(12,4) default NULL,
    `price` decimal(12,4) default NULL,
    `weight` decimal(12,4) default NULL,
    `qty` decimal(12,4) default NULL,
    `product_id` int(10) default NULL,
    `order_item_id` int(10) default NULL,
    `additional_data` text,
    `description` text,
    `name` varchar(255) default NULL,
    `sku` varchar(255) default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Shipping tracking */

CREATE TABLE `{$installer->getTable('sales_flat_shipment_track')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `weight` decimal(12,4) default NULL,
    `qty` decimal(12,4) default NULL,
    `order_id` int(10) unsigned NOT NULL,
    `number` text,
    `description` text,
    `title` varchar(255) default NULL,
    `carrier_code` varchar(32) default NULL,
    `created_at` datetime default NULL,
    `updated_at` datetime default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_PARENT_ID` (`parent_id`),
    KEY `IDX_ORDER_ID` (`order_id`),
    KEY `IDX_CREATED_AT` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Shipment Comment */
CREATE TABLE `{$installer->getTable('sales_flat_shipment_comment')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `is_customer_notified` int(10) default NULL,
    `comment` text,
    `created_at` datetime default NULL,
    `user_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`entity_id`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Invoice Main Table */
CREATE TABLE `{$installer->getTable('sales_flat_invoice')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `store_id` smallint(5) unsigned default NULL,
    `base_grand_total` decimal(12,4) default NULL,
    `store_to_order_rate` decimal(12,4) default NULL,
    `base_discount_amount` decimal(12,4) default NULL,
    `base_to_order_rate` decimal(12,4) default NULL,
    `grand_total` decimal(12,4) default NULL,
    `shipping_amount` decimal(12,4) default NULL,
    `store_to_base_rate` decimal(12,4) default NULL,
    `base_shipping_amount` decimal(12,4) default NULL,
    `total_qty` decimal(12,4) default NULL,
    `base_to_global_rate` decimal(12,4) default NULL,
    `subtotal` decimal(12,4) default NULL,
    `base_subtotal` decimal(12,4) default NULL,
    `discount_amount` decimal(12,4) default NULL,
    `is_used_for_refund` tinyint(1) unsigned default NULL,
    `order_id` int(10) unsigned NOT NULL,
    `email_sent` tinyint(1) unsigned default NULL,
    `can_void_flag` tinyint(1) unsigned default NULL,
    `state` int(10) default NULL,
    `shipping_address_id` int(10) default NULL,
    `cybersource_token` varchar(255) default NULL,
    `store_currency_code` char(3) default NULL,
    `transaction_id` varchar(255) default NULL,
    `order_currency_code` char(3) default NULL,
    `base_currency_code` char(3) default NULL,
    `global_currency_code` char(3) default NULL,
    `increment_id` varchar(50) default NULL,
    `created_at` datetime default NULL,
    `updated_at` datetime default NULL,
    `user_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_GRAND_TOTAL` (`grand_total`),
    KEY `IDX_ORDER_ID` (`order_id`),
    KEY `IDX_STATE` (`state`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_CREATED_AT` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Invoices Grid */
CREATE TABLE `{$installer->getTable('sales_flat_invoice_grid')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `store_id` smallint(5) unsigned default NULL,
    `base_grand_total` decimal(12,4) default NULL,
    `grand_total` decimal(12,4) default NULL,
    `order_id` int(10) unsigned NOT NULL,
    `state` int(10) default NULL,
    `store_currency_code` char(3) default NULL,
    `order_currency_code` char(3) default NULL,
    `base_currency_code` char(3) default NULL,
    `global_currency_code` char(3) default NULL,
    `increment_id` varchar(50) default NULL,
    `order_increment_id` varchar(50) default NULL,
    `created_at` datetime default NULL,
    `order_created_at` datetime default NULL,
    `shipping_name` varchar(255) default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_GRAND_TOTAL` (`grand_total`),
    KEY `IDX_ORDER_ID` (`order_id`),
    KEY `IDX_STATE` (`state`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_ORDER_INCREMENT_ID` (`order_increment_id`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_ORDER_CREATED_AT` (`order_created_at`),
    KEY `IDX_SHIPPING_NAME` (`shipping_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Invoice Items */

CREATE TABLE `{$installer->getTable('sales_flat_invoice_item')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `base_price` decimal(12,4) default NULL,
    `base_row_total` decimal(12,4) default NULL,
    `discount_amount` decimal(12,4) default NULL,
    `row_total` decimal(12,4) default NULL,
    `base_discount_amount` decimal(12,4) default NULL,
    `qty` decimal(12,4) default NULL,
    `base_cost` decimal(12,4) default NULL,
    `price` decimal(12,4) default NULL,
    `product_id` int(10) default NULL,
    `order_item_id` int(10) default NULL,
    `additional_data` text,
    `description` text,
    `sku` varchar(255) default NULL,
    `name` varchar(255) default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Invoice Comments */
CREATE TABLE `{$installer->getTable('sales_flat_invoice_comment')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `is_customer_notified` tinyint(1) unsigned default NULL,
    `comment` text,
    `created_at` datetime default NULL,
    `user_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`entity_id`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* CreditMemo Main table */
CREATE TABLE `{$installer->getTable('sales_flat_creditmemo')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `store_id` smallint(5) unsigned default NULL,
    `adjustment_positive` decimal(12,4) default NULL,
    `store_to_order_rate` decimal(12,4) default NULL,
    `base_discount_amount` decimal(12,4) default NULL,
    `base_to_order_rate` decimal(12,4) default NULL,
    `grand_total` decimal(12,4) default NULL,
    `base_adjustment_negative` decimal(12,4) default NULL,
    `shipping_amount` decimal(12,4) default NULL,
    `adjustment_negative` decimal(12,4) default NULL,
    `base_shipping_amount` decimal(12,4) default NULL,
    `store_to_base_rate` decimal(12,4) default NULL,
    `base_to_global_rate` decimal(12,4) default NULL,
    `base_adjustment` decimal(12,4) default NULL,
    `base_subtotal` decimal(12,4) default NULL,
    `discount_amount` decimal(12,4) default NULL,
    `subtotal` decimal(12,4) default NULL,
    `adjustment` decimal(12,4) default NULL,
    `base_grand_total` decimal(12,4) default NULL,
    `base_adjustment_positive` decimal(12,4) default NULL,
    `order_id` int(10) unsigned NOT NULL,
    `email_sent` tinyint(1) unsigned default NULL,
    `creditmemo_status` int(10) default NULL,
    `state` int(10) default NULL,
    `shipping_address_id` int(10) default NULL,
    `invoice_id` int(10) default NULL,
    `cybersource_token` varchar(255) default NULL,
    `store_currency_code` char(3) default NULL,
    `order_currency_code` char(3) default NULL,
    `base_currency_code` char(3) default NULL,
    `global_currency_code` char(3) default NULL,
    `transaction_id` varchar(255) default NULL,
    `increment_id` varchar(50) default NULL,
    `created_at` datetime default NULL,
    `updated_at` datetime default NULL,
    `user_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_ORDER_ID` (`order_id`),
    KEY `IDX_CREDITMEMO_STATUS` (`creditmemo_status`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_STATE` (`state`),
    KEY `IDX_CREATED_AT` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* CreditMemo Grid */
CREATE TABLE `{$installer->getTable('sales_flat_creditmemo_grid')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `store_id` smallint(5) unsigned default NULL,
    `store_to_order_rate` decimal(12,4) default NULL,
    `base_to_order_rate` decimal(12,4) default NULL,
    `grand_total` decimal(12,4) default NULL,
    `store_to_base_rate` decimal(12,4) default NULL,
    `base_to_global_rate` decimal(12,4) default NULL,
    `base_grand_total` decimal(12,4) default NULL,
    `order_id` int(10) unsigned NOT NULL,
    `creditmemo_status` int(10) default NULL,
    `state` int(10) default NULL,
    `invoice_id` int(10) default NULL,
    `store_currency_code` char(3) default NULL,
    `order_currency_code` char(3) default NULL,
    `base_currency_code` char(3) default NULL,
    `global_currency_code` char(3) default NULL,
    `increment_id` varchar(50) default NULL,
    `order_increment_id` varchar(50) default NULL,
    `created_at` datetime default NULL,
    `order_created_at` datetime default NULL,
    `shipping_name` varchar(255) default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_STORE_ID` (`store_id`),
    KEY `IDX_GRAND_TOTAL` (`grand_total`),
    KEY `IDX_BASE_GRAND_TOTAL` (`base_grand_total`),
    KEY `IDX_ORDER_ID` (`order_id`),
    KEY `IDX_CREDITMEMO_STATUS` (`creditmemo_status`),
    KEY `IDX_STATE` (`state`),
    KEY `IDX_INCREMENT_ID` (`increment_id`),
    KEY `IDX_ORDER_INCREMENT_ID` (`order_increment_id`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_ORDER_CREATED_AT` (`order_created_at`),
    KEY `IDX_SHIPPING_NAME` (`shipping_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* CreditMemo Item */

CREATE TABLE `{$installer->getTable('sales_flat_creditmemo_item')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `base_price` decimal(12,4) default NULL,
    `base_row_total` decimal(12,4) default NULL,
    `discount_amount` decimal(12,4) default NULL,
    `row_total` decimal(12,4) default NULL,
    `base_discount_amount` decimal(12,4) default NULL,
    `qty` decimal(12,4) default NULL,
    `base_cost` decimal(12,4) default NULL,
    `price` decimal(12,4) default NULL,
    `product_id` int(10) default NULL,
    `order_item_id` int(10) default NULL,
    `additional_data` text,
    `description` text,
    `sku` varchar(255) default NULL,
    `name` varchar(255) default NULL,
    PRIMARY KEY (`entity_id`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* CreditMemo Comment */

CREATE TABLE `{$installer->getTable('sales_flat_creditmemo_comment')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `is_customer_notified` int(10) default NULL,
    `comment` text,
    `created_at` datetime default NULL,
    `user_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`entity_id`),
    KEY `IDX_CREATED_AT` (`created_at`),
    KEY `IDX_PARENT_ID` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_flat_quote')}` (
  `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `converted_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) unsigned DEFAULT '1',
  `is_virtual` tinyint(1) unsigned DEFAULT '0',
  `is_multi_shipping` tinyint(1) unsigned DEFAULT '0',
  `items_count` int(10) unsigned DEFAULT '0',
  `items_qty` decimal(12,4) DEFAULT '0.0000',
  `orig_order_id` int(10) unsigned DEFAULT '0',
  `store_to_base_rate` decimal(12,4) DEFAULT '0.0000',
  `store_to_quote_rate` decimal(12,4) DEFAULT '0.0000',
  `base_currency_code` varchar(255) DEFAULT NULL,
  `store_currency_code` varchar(255) DEFAULT NULL,
  `quote_currency_code` varchar(255) DEFAULT NULL,
  `grand_total` decimal(12,4) DEFAULT '0.0000',
  `base_grand_total` decimal(12,4) DEFAULT '0.0000',
  `checkout_method` varchar(255) DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT '0',
  `customer_group_id` int(10) unsigned DEFAULT '0',
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_prefix` varchar(40) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_suffix` varchar(40) DEFAULT NULL,
  `customer_dob` datetime DEFAULT NULL,
  `customer_comment` varchar(255) DEFAULT NULL,
  `customer_note` varchar(255) DEFAULT NULL,
  `customer_note_notify` tinyint(1) unsigned DEFAULT '1',
  `customer_is_guest` tinyint(1) unsigned DEFAULT '0',
  `remote_ip` varchar(32) DEFAULT NULL,
  `applied_rule_ids` varchar(255) DEFAULT NULL,
  `reserved_order_id` varchar(64) DEFAULT '',
  `password_hash` varchar(255) DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `global_currency_code` varchar(255) DEFAULT NULL,
  `base_to_global_rate` decimal(12,4) DEFAULT NULL,
  `base_to_quote_rate` decimal(12,4) DEFAULT NULL,
  `customer_gender` varchar(255) DEFAULT NULL,
  `subtotal` decimal(12,4) DEFAULT NULL,
  `base_subtotal` decimal(12,4) DEFAULT NULL,
  `subtotal_with_discount` decimal(12,4) DEFAULT NULL,
  `base_subtotal_with_discount` decimal(12,4) DEFAULT NULL,
  `is_changed` int(10) unsigned DEFAULT NULL,
  `trigger_recollect` tinyint(1) NOT NULL DEFAULT '0',
  `ext_shipping_info` text,
  `gift_message_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`entity_id`),
  KEY `IDX_CUSTOMER` (`customer_id`,`store_id`,`is_active`),
  CONSTRAINT `FK_SALES_QUOTE_STORE` FOREIGN KEY (`store_id`)
    REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_flat_quote_address')}` (
  `address_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `customer_id` int(10) unsigned DEFAULT NULL,
  `save_in_address_book` tinyint(1) DEFAULT '0',
  `customer_address_id` int(10) unsigned DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `prefix` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `suffix` varchar(40) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `area_id` int(10) unsigned DEFAULT NULL,
  `area` varchar(255) default NULL,
  `city_id` int(10) unsigned DEFAULT NULL,
  `city` varchar(255) default NULL,
  `region` varchar(255) default NULL,
  `region_id` int(10) unsigned DEFAULT NULL,
  `country_id` varchar(255) default NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `free_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `collect_shipping_rates` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_method` varchar(255) NOT NULL DEFAULT '',
  `shipping_description` varchar(255) NOT NULL DEFAULT '',
  `weight` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `subtotal` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_subtotal` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `subtotal_with_discount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_subtotal_with_discount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `shipping_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `grand_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_grand_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `customer_notes` text,
  `discount_description` varchar(255) DEFAULT NULL,
  `shipping_discount_amount` decimal(12,4) DEFAULT NULL,
  `base_shipping_discount_amount` decimal(12,4) DEFAULT NULL,
  `gift_message_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`address_id`),
  CONSTRAINT `FK_SALES_QUOTE_ADDRESS_SALES_QUOTE` FOREIGN KEY (`quote_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_flat_quote_address_item')}` (
  `address_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_item_id` int(10) unsigned DEFAULT NULL,
  `quote_address_id` int(10) unsigned NOT NULL DEFAULT '0',
  `quote_item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `applied_rule_ids` text,
  `additional_data` text,
  `weight` decimal(12,4) DEFAULT '0.0000',
  `qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `row_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_row_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `row_total_with_discount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `row_weight` decimal(12,4) DEFAULT '0.0000',
  `product_id` int(10) unsigned DEFAULT NULL,
  `super_product_id` int(10) unsigned DEFAULT NULL,
  `parent_product_id` int(10) unsigned DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `free_shipping` int(10) unsigned DEFAULT NULL,
  `is_qty_decimal` int(10) unsigned DEFAULT NULL,
  `price` decimal(12,4) DEFAULT NULL,
  `discount_percent` decimal(12,4) DEFAULT NULL,
  `no_discount` int(10) unsigned DEFAULT NULL,
  `base_price` decimal(12,4) DEFAULT NULL,
  `base_cost` decimal(12,4) DEFAULT NULL,
  `gift_message_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`address_item_id`),
  CONSTRAINT `FK_QUOTE_ADDRESS_ITEM_QUOTE_ADDRESS` FOREIGN KEY (`quote_address_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote_address')}` (`address_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SALES_FLAT_QUOTE_ADDRESS_ITEM_PARENT` FOREIGN KEY (`parent_item_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote_address_item')}` (`address_item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SALES_QUOTE_ADDRESS_ITEM_QUOTE_ITEM` FOREIGN KEY (`quote_item_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote_item')}` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_flat_quote_item')}` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `product_id` int(10) unsigned DEFAULT NULL,
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `parent_item_id` int(10) unsigned DEFAULT NULL,
  `is_virtual` tinyint(1) unsigned DEFAULT NULL,
  `sku` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `applied_rule_ids` text,
  `additional_data` text,
  `free_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_qty_decimal` tinyint(1) unsigned DEFAULT NULL,
  `no_discount` tinyint(1) unsigned DEFAULT '0',
  `weight` decimal(12,4) DEFAULT '0.0000',
  `qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `custom_price` decimal(12,4) DEFAULT NULL,
  `discount_percent` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `row_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_row_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `row_total_with_discount` decimal(12,4) DEFAULT '0.0000',
  `row_weight` decimal(12,4) DEFAULT '0.0000',
  `product_type` varchar(255) DEFAULT NULL,
  `original_custom_price` decimal(12,4) DEFAULT NULL,
  `redirect_url` varchar(255) DEFAULT NULL,
  `base_cost` decimal(12,4) DEFAULT NULL,
  `gift_message_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  CONSTRAINT `FK_SALES_FLAT_QUOTE_ITEM_PARENT_ITEM` FOREIGN KEY (`parent_item_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote_item')}` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SALES_QUOTE_ITEM_CATALOG_PRODUCT_ENTITY` FOREIGN KEY (`product_id`)
    REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SALES_QUOTE_ITEM_SALES_QUOTE` FOREIGN KEY (`quote_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SALES_QUOTE_ITEM_STORE` FOREIGN KEY (`store_id`)
    REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_flat_quote_item_option')}` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`option_id`),
  CONSTRAINT `FK_SALES_QUOTE_ITEM_OPTION_ITEM_ID` FOREIGN KEY (`item_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote_item')}` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Additional options for quote item';

CREATE TABLE `{$installer->getTable('sales_flat_quote_payment')}` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `method` varchar(255) DEFAULT '',
  `cod_type` varchar(255) DEFAULT '',
  `cybersource_token` varchar(255) DEFAULT '',
  `po_number` varchar(255) DEFAULT '',
  `additional_data` text,
  `additional_information` text,
  `ideal_issuer_id` varchar(255) DEFAULT NULL,
  `ideal_issuer_list` text,
  PRIMARY KEY (`payment_id`),
  CONSTRAINT `FK_SALES_QUOTE_PAYMENT_SALES_QUOTE` FOREIGN KEY (`quote_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_flat_quote_shipping_rate')}` (
  `rate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `address_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `carrier` varchar(255) DEFAULT NULL,
  `carrier_title` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `method_description` text,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `error_message` text,
  `method_title` text,
  PRIMARY KEY (`rate_id`),
  CONSTRAINT `FK_SALES_QUOTE_SHIPPING_RATE_ADDRESS` FOREIGN KEY (`address_id`)
    REFERENCES `{$installer->getTable('sales_flat_quote_address')}` (`address_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_invoiced_aggregated')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` date NOT NULL DEFAULT '0000-00-00',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT '',
  `orders_count` int(11) NOT NULL DEFAULT '0',
  `orders_invoiced` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `invoiced` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `invoiced_captured` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `invoiced_not_captured` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`),
  KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_invoiced_aggregated_order')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` date NOT NULL DEFAULT '0000-00-00',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT '',
  `orders_count` int(11) NOT NULL DEFAULT '0',
  `orders_invoiced` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `invoiced` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `invoiced_captured` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `invoiced_not_captured` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`),
  KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_order_aggregated_created')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` date NOT NULL DEFAULT '0000-00-00',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT '',
  `orders_count` int(11) NOT NULL DEFAULT '0',
  `total_qty_ordered` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_profit_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_subtotal_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_grand_total_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_invoiced_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_refunded_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_canceled_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_subtotal_invoiced_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_subtotal_refunded_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_subtotal_canceled_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_discount_invoiced_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_discount_canceled_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_discount_refunded_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_invoiced_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_canceled_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_refunded_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`),
  KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_payment_transaction')}` (
  `transaction_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `payment_id` int(10) unsigned NOT NULL DEFAULT '0',
  `txn_id` varchar(100) NOT NULL DEFAULT '',
  `parent_txn_id` varchar(100) DEFAULT NULL,
  `txn_type` varchar(15) NOT NULL DEFAULT '',
  `is_closed` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `additional_information` blob,
  PRIMARY KEY (`transaction_id`),
  UNIQUE KEY `UNQ_ORDER_PAYMENT_TXN` (`order_id`, `payment_id`,`txn_id`),
  KEY `IDX_ORDER_ID` (`order_id`),
  KEY `IDX_PARENT_ID` (`parent_id`),
  KEY `IDX_PAYMENT_ID` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_refunded_aggregated')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` date NOT NULL DEFAULT '0000-00-00',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT '',
  `orders_count` int(11) NOT NULL DEFAULT '0',
  `refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `online_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `offline_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`),
  KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_refunded_aggregated_order')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` date NOT NULL DEFAULT '0000-00-00',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT '',
  `orders_count` int(11) NOT NULL DEFAULT '0',
  `refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `online_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `offline_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`),
  KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_shipping_aggregated')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` date NOT NULL DEFAULT '0000-00-00',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT '',
  `shipping_description` varchar(255) NOT NULL DEFAULT '',
  `orders_count` int(11) NOT NULL DEFAULT '0',
  `total_shipping` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_shipping_actual` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`,`shipping_description`),
  KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('sales_shipping_aggregated_order')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` date NOT NULL DEFAULT '0000-00-00',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT '',
  `shipping_description` varchar(255) NOT NULL DEFAULT '',
  `orders_count` int(11) NOT NULL DEFAULT '0',
  `total_shipping` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_shipping_actual` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`,`shipping_description`),
  KEY `IDX_STORE_ID` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE `{$installer->getTable('sales/order_aggregated_created')}`;
CREATE TABLE `{$installer->getTable('sales/order_aggregated_created')}` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `period` DATE NOT NULL DEFAULT '0000-00-00',
  `store_id` SMALLINT(5) UNSIGNED DEFAULT NULL,
  `order_status` VARCHAR(50) NOT NULL DEFAULT '',
  `orders_count` INT(11) NOT NULL DEFAULT '0',
  `total_qty_ordered` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_qty_invoiced` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_income_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_revenue_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_profit_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_invoiced_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_canceled_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_paid_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_refunded_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_shipping_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_shipping_amount_actual` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_discount_amount` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `total_discount_amount_actual` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`,`order_status`),
  KEY `IDX_STORE_ID` (`store_id`),
  CONSTRAINT `FK_SALES_ORDER_AGGREGATED_CREATED` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

");

$constraints = array(
    'sales_flat_order' => array(
        'customer' => array('customer_id', 'customer_entity', 'entity_id', 'set null'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_flat_order_grid' => array(
        'parent' => array('entity_id', 'sales_flat_order', 'entity_id'),
        'customer' => array('customer_id', 'customer_entity', 'entity_id', 'set null'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_flat_order_item' => array(
        'parent' => array('order_id', 'sales_flat_order', 'entity_id'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_flat_order_address' => array(
        'parent' => array('parent_id', 'sales_flat_order', 'entity_id'),
    ),
    'sales_flat_order_payment' => array(
        'parent' => array('parent_id', 'sales_flat_order', 'entity_id'),
    ),
    'sales_flat_order_status_history' => array(
        'parent' => array('parent_id', 'sales_flat_order', 'entity_id'),
    ),
    'sales_flat_shipment' => array(
        'parent' => array('order_id', 'sales_flat_order', 'entity_id'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null')
    ),
    'sales_flat_shipment_grid' => array(
        'parent' => array('entity_id', 'sales_flat_shipment', 'entity_id'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null')
    ),
    'sales_flat_shipment_track' => array(
        'parent' => array('parent_id', 'sales_flat_shipment', 'entity_id'),
    ),
    'sales_flat_shipment_item' => array(
        'parent' => array('parent_id', 'sales_flat_shipment', 'entity_id'),
    ),
    'sales_flat_shipment_comment' => array(
        'parent' => array('parent_id', 'sales_flat_shipment', 'entity_id'),
    ),
    'sales_flat_invoice' => array(
        'parent' => array('order_id', 'sales_flat_order', 'entity_id'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null')
    ),
    'sales_flat_invoice_grid' => array(
        'parent' => array('entity_id', 'sales_flat_invoice', 'entity_id'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null')
    ),
    'sales_flat_invoice_item' => array(
        'parent' => array('parent_id', 'sales_flat_invoice', 'entity_id'),
    ),
    'sales_flat_invoice_comment' => array(
        'parent' => array('parent_id', 'sales_flat_invoice', 'entity_id'),
    ),
    'sales_flat_creditmemo' => array(
        'parent' => array('order_id', 'sales_flat_order', 'entity_id'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null')
    ),
    'sales_flat_creditmemo_grid' => array(
        'parent' => array('entity_id', 'sales_flat_creditmemo', 'entity_id'),
        'store' => array('store_id', 'core_store', 'store_id', 'set null')
    ),
    'sales_flat_creditmemo_item' => array(
        'parent' => array('parent_id', 'sales_flat_creditmemo', 'entity_id'),
    ),
    'sales_flat_creditmemo_comment' => array(
        'parent' => array('parent_id', 'sales_flat_creditmemo', 'entity_id'),
    ),
    'sales_payment_transaction' => array(
        'parent' => array('parent_id', 'sales_payment_transaction', 'transaction_id'),
        'order' => array('order_id', 'sales_flat_order', 'entity_id'),
        'payment' => array('payment_id', 'sales_flat_order_payment', 'entity_id'),
    ),
    'sales_invoiced_aggregated' => array(
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_invoiced_aggregated_order' => array(
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_order_aggregated_created' => array(
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_refunded_aggregated' => array(
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_refunded_aggregated_order' => array(
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_shipping_aggregated' => array(
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    ),
    'sales_shipping_aggregated_order' => array(
        'store' => array('store_id', 'core_store', 'store_id', 'set null'),
    )
);

foreach ($constraints as $table => $list) {
    foreach ($list as $code => $constraint) {
        $constraint[1] = $installer->getTable($constraint[1]);
        array_unshift($constraint, $installer->getTable($table));
        array_unshift($constraint, strtoupper($table . '_' . $code));

        call_user_func_array(array($installer->getConnection(), 'addConstraint'), $constraint);
    }
}

// Add eav entity types
$installer->addEntityType('order', array(
    'entity_model'          => 'sales/order',
    'table'                 =>'sales/order',
    'increment_model'       =>'eav/entity_increment_numeric',
    'increment_per_store'   =>true
));

$installer->addEntityType('invoice', array(
    'entity_model'          => 'sales/order_invoice',
    'table'                 =>'sales/invoice',
    'increment_model'       =>'eav/entity_increment_numeric',
    'increment_per_store'   =>true
));

$installer->addEntityType('creditmemo', array(
    'entity_model'          => 'sales/order_creditmemo',
    'table'                 =>'sales/creditmemo',
    'increment_model'       =>'eav/entity_increment_numeric',
    'increment_per_store'   =>true
));

$installer->addEntityType('shipment', array(
    'entity_model'          => 'sales/order_shipment',
    'table'                 =>'sales/shipment',
    'increment_model'       =>'eav/entity_increment_numeric',
    'increment_per_store'   =>true
));

$installer->getConnection()->addColumn($installer->getTable('sales_flat_order_grid'), 'updated_at', 'datetime default NULL');
$installer->getConnection()->addKey($installer->getTable('sales_flat_order_grid'), 'IDX_UPDATED_AT' ,'updated_at');
$installer->run("
    UPDATE {$installer->getTable('sales_flat_order_grid')} AS g
        JOIN {$installer->getTable('sales_flat_order')} AS o ON g.entity_id=o.entity_id
        SET g.updated_at=o.updated_at
");

$installer->getConnection()->addKey($installer->getTable('sales_flat_order'), 'IDX_UPDATED_AT', 'updated_at');

$installer->getConnection()->addKey($installer->getTable('sales_flat_shipment'), 'IDX_CREATED_AT', 'created_at');
$installer->getConnection()->addKey($installer->getTable('sales_flat_shipment'), 'IDX_UPDATED_AT', 'updated_at');

foreach (array('daily', 'monthly', 'yearly') as $frequency) {
    $tableName = $installer->getTable('sales/bestsellers_aggregated_' . $frequency);

    $installer->run("
    CREATE TABLE `{$tableName}` (
      `id` int(11) unsigned NOT NULL auto_increment,
      `period` date NOT NULL DEFAULT '0000-00-00',
      `store_id` smallint(5) unsigned NULL DEFAULT NULL,
      `product_id` int(10) unsigned NULL DEFAULT NULL,
      `product_name` varchar(255) NOT NULL DEFAULT '',
      `product_price` decimal(12,4) NOT NULL DEFAULT '0',
      `qty_ordered` decimal(12,4) NOT NULL DEFAULT '0.0000',
      `rating_pos` smallint(5) unsigned NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `UNQ_PERIOD_STORE_PRODUCT` (`period`, `store_id`, `product_id`),
      KEY `IDX_STORE_ID` (`store_id`),
      KEY `IDX_PRODUCT_ID` (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

    $installer->getConnection()->addConstraint(
        'PRODUCT_ORDERED_AGGREGATED_' . strtoupper($frequency) . '_STORE_ID',
        $tableName,
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        'SET NULL'
    );

    $installer->getConnection()->addConstraint(
        'PRODUCT_ORDERED_AGGREGATED_' . strtoupper($frequency) . '_PRODUCT_ID',
        $tableName,
        'product_id',
        $installer->getTable('catalog/product'),
        'entity_id',
        'SET NULL'
    );
}

$orderHistoryTable = $installer->getTable('sales_flat_order_status_history');
$installer->getConnection()->addColumn(
    $orderHistoryTable,
    'is_visible_on_front',
    "tinyint(1) UNSIGNED NOT NULL default '0' after `is_customer_notified`"
);
$installer->run("UPDATE {$orderHistoryTable} SET
    is_visible_on_front = (is_customer_notified = 1 AND comment IS NOT NULL AND comment <> '');"
);

$orderTable = $installer->getTable('sales/order');

$installer->run("
UPDATE {$orderTable} SET
    base_discount_canceled = (ABS(base_discount_amount) - IFNULL(base_discount_invoiced, 0)),
    base_total_canceled = (base_subtotal_canceled + IFNULL(base_shipping_canceled, 0) - IFNULL(ABS(base_discount_amount) - IFNULL(base_discount_invoiced, 0), 0)),
    discount_canceled = (ABS(discount_amount) - IFNULL(discount_invoiced, 0)),
    total_canceled = (subtotal_canceled + IFNULL(shipping_canceled, 0) - IFNULL(ABS(discount_amount) - IFNULL(discount_invoiced, 0), 0))
");

$orderGridTable             = $installer->getTable('sales/order_grid');
$orderTable                 = $installer->getTable('sales/order');
$paymentTransactionTable    = $installer->getTable('sales/payment_transaction');
$profileTable               = $installer->getTable('sales_recurring_profile');
$orderItemTable             = $installer->getTable('sales_flat_order_item');
$flatOrderTable             = $installer->getTable('sales_flat_order');
$profileOrderTable          = $installer->getTable('sales_recurring_profile_order');
$customerEntityTable        = $installer->getTable('customer_entity');
$coreStoreTable             = $installer->getTable('core_store');
$shippingAgreementTable      = $installer->getTable('sales/shipping_agreement');
$shippingAgreementOrderTable = $installer->getTable('sales/shipping_agreement_order');

//-------
$installer->getConnection()->addColumn($orderGridTable,
    'store_name', 'varchar(255) null default null AFTER `store_id`');

$installer->run("
    UPDATE {$orderGridTable} AS og
        INNER JOIN  {$orderTable} AS o on (og.entity_id=o.entity_id)
    SET
        og.store_name = o.store_name
");

//-------
$installer->getConnection()->addColumn($paymentTransactionTable,
    'created_at', 'DATETIME NULL');

//-------
$this->getConnection()->addColumn($orderItemTable, 'is_nominal', 'int NOT NULL DEFAULT \'0\'');

//-------
$installer->run("
    CREATE TABLE `{$shippingAgreementTable}` (
      `agreement_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `customer_id` int(10) unsigned NOT NULL,
      `method_code` varchar(32) NOT NULL,
      `reference_id` varchar(32) NOT NULL,
      `status` varchar(20) NOT NULL,
      `created_at` datetime NOT NULL,
      `updated_at` datetime DEFAULT NULL,
      PRIMARY KEY (`agreement_id`),
      KEY `IDX_CUSTOMER` (`customer_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->getConnection()->addConstraint(
    'FK_SHIPPING_AGREEMENT_CUSTOMER',
    $shippingAgreementTable,
    'customer_id',
    $installer->getTable('customer/entity'),
    'entity_id'

);

//-------
$installer->run("
    CREATE TABLE `{$shippingAgreementOrderTable}` (
      `agreement_id` int(10) unsigned NOT NULL,
      `order_id` int(10) unsigned NOT NULL,
      UNIQUE KEY `UNQ_SHIPPING_AGREEMENT_ORDER` (`agreement_id`,`order_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->getConnection()->addConstraint(
    'FK_SHIPPING_AGREEMENT_ORDER_AGREEMENT',
    $shippingAgreementOrderTable,
    'agreement_id',
    $shippingAgreementTable,
    'agreement_id'
);

$installer->getConnection()->addConstraint(
    'FK_SHIPPING_AGREEMENT_ORDER_ORDER',
    $shippingAgreementOrderTable,
    'order_id',
    $orderTable,
    'entity_id'
);

//-------

$this->run("
CREATE TABLE `{$profileTable}` (
  `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(20) NOT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `method_code` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reference_id` varchar(32) DEFAULT NULL,
  `subscriber_name` varchar(150) DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `internal_reference_id` varchar(42) NOT NULL,
  `schedule_description` varchar(255) NOT NULL,
  `suspension_threshold` smallint(6) unsigned DEFAULT NULL,
  `bill_failed_later` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `period_unit` varchar(20) NOT NULL,
  `period_frequency` tinyint(3) unsigned DEFAULT NULL,
  `period_max_cycles` tinyint(3) unsigned DEFAULT NULL,
  `trial_period_unit` varchar(20) DEFAULT NULL,
  `trial_period_frequency` tinyint(3) unsigned DEFAULT NULL,
  `trial_period_max_cycles` tinyint(3) unsigned DEFAULT NULL,
  `trial_shipping_amount` double(12,4) unsigned DEFAULT NULL,
  `currency_code` char(3) NOT NULL,
  `shipping_amount` decimal(12,4) unsigned DEFAULT NULL,
  `init_amount` decimal(12,4) unsigned DEFAULT NULL,
  `init_may_fail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_info` text NOT NULL,
  `order_item_info` text NOT NULL,
  `shipping_address_info` text DEFAULT NULL,
  `profile_vendor_info` text DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  PRIMARY KEY (`profile_id`),
  UNIQUE KEY `UNQ_INTERNAL_REF_ID` (`internal_reference_id`),
  KEY `IDX_RECURRING_PROFILE_CUSTOMER` (`customer_id`),
  KEY `IDX_RECURRING_PROFILE_STORE` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$this->getConnection()->addConstraint('FK_RECURRING_PROFILE_CUSTOMER', $profileTable, 'customer_id',
    $customerEntityTable, 'entity_id', 'SET NULL'
);

$this->getConnection()->addConstraint('FK_RECURRING_PROFILE_STORE', $profileTable, 'store_id',
    $coreStoreTable, 'store_id', 'SET NULL'
);

$this->run("
CREATE TABLE `{$profileOrderTable}` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`link_id`),
  UNIQUE KEY `UNQ_PROFILE_ORDER` (`profile_id`,`order_id`),
  KEY `IDX_ORDER` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$this->getConnection()->addConstraint('FK_RECURRING_PROFILE_ORDER_PROFILE', $profileOrderTable, 'profile_id',
    $profileTable, 'profile_id'
);

$this->getConnection()->addConstraint('FK_RECURRING_PROFILE_ORDER_ORDER', $profileOrderTable, 'order_id',
    $flatOrderTable, 'entity_id'
);

$shippingAgreementTable = $installer->getTable('sales/shipping_agreement');

$installer->getConnection()->addColumn($shippingAgreementTable,
    'store_id', 'smallint(5) unsigned DEFAULT NULL');

$installer->getConnection()->addConstraint(
    'FK_SHIPPING_AGREEMENT_STORE',
    $shippingAgreementTable,
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
    'SET NULL',
    'CASCADE'
);

$installer->getConnection()->addColumn($installer->getTable('sales/invoice_comment'), 'is_visible_on_front',
    'tinyint(1) unsigned not null default 0 after `is_customer_notified`');
$installer->getConnection()->addColumn($installer->getTable('sales/shipment_comment'), 'is_visible_on_front',
    'tinyint(1) unsigned not null default 0 after `is_customer_notified`');
$installer->getConnection()->addColumn($installer->getTable('sales/creditmemo_comment'), 'is_visible_on_front',
    'tinyint(1) unsigned not null default 0 after `is_customer_notified`');

$shippingAgreementTable = $installer->getTable('sales/shipping_agreement');

$installer->getConnection()->addColumn($shippingAgreementTable,
    'agreement_label', 'varchar(255)');

$installer->getConnection()->addKey($installer->getTable('sales/quote'), 'IDX_IS_ACTIVE', 'is_active');
$installer->getConnection()->addKey($installer->getTable('sales/order_item'), 'IDX_PRODUCT_ID', 'product_id');

// Setup data to configure
$frequencies = array(
    Mage_Sales_Model_Mysql4_Report_Bestsellers::AGGREGATION_DAILY,
    Mage_Sales_Model_Mysql4_Report_Bestsellers::AGGREGATION_MONTHLY,
    Mage_Sales_Model_Mysql4_Report_Bestsellers::AGGREGATION_YEARLY
);

$foreignKeys = array(
    array(
        'name' => 'FK_PRODUCT_ORDERED_AGGREGATED_%s_STORE_ID',
        'column' => 'store_id',
        'refTable' => 'core/store',
        'refColumn' => 'store_id'
    ),
    array(
        'name' => 'FK_PRODUCT_ORDERED_AGGREGATED_%s_PRODUCT_ID',
        'column' => 'product_id',
        'refTable' => 'catalog/product',
        'refColumn' => 'entity_id'
    )
);

/*
 * Alter foreign keys to add 'CASCADE' instead of 'SET_NULL' action
 * Also remove all wrong report records with NULL in 'product_id' field
 */
$connection = $installer->getConnection();
foreach ($frequencies as $frequency) {
    $tableName = $installer->getTable('sales/bestsellers_aggregated_' . $frequency);

    foreach ($foreignKeys as $fkInfo) {
        $connection->addConstraint(
            sprintf($fkInfo['name'], strtoupper($frequency)),
            $tableName,
            $fkInfo['column'],
            $installer->getTable($fkInfo['refTable']),
            $fkInfo['refColumn']
        );
    }

    $connection->delete($tableName, 'product_id IS NULL');
}

foreach(array(
        'sales/order', 'sales/order_grid', 'sales/creditmemo', 'sales/creditmemo_grid',
        'sales/invoice', 'sales/invoice_grid', 'sales/shipment','sales/shipment_grid',
    ) as $table) {
    $tableName = $installer->getTable($table);
    $installer->getConnection()->dropKey($tableName, 'IDX_INCREMENT_ID');
    $installer->getConnection()->addKey($tableName, 'UNQ_INCREMENT_ID', 'increment_id', 'unique');
}

$statusTable        = $installer->getTable('sales/order_status');
$statusStateTable   = $installer->getTable('sales/order_status_state');
$statusLabelTable   = $installer->getTable('sales/order_status_label');

$installer->run("
CREATE TABLE `{$statusTable}` (
  `status` varchar(32) NOT NULL,
  `label` varchar(128) NOT NULL,
  PRIMARY KEY (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");

$statuses = Mage::getConfig()->getNode('global/sales/order/statuses')->asArray();
$data = array();
foreach ($statuses as $code => $info) {
    $data[] = array(
        'status'    => $code,
        'label'     => $info['label']
    );
}
$installer->getConnection()->insertArray($statusTable, array('status', 'label'), $data);

$installer->run("
CREATE TABLE `{$statusStateTable}` (
  `status` varchar(32) NOT NULL,
  `state` varchar(32) NOT NULL,
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`status`,`state`),
  CONSTRAINT `FK_SALES_ORDER_STATUS_STATE_STATUS` FOREIGN KEY (`status`)
    REFERENCES `{$statusTable}` (`status`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
$states     = Mage::getConfig()->getNode('global/sales/order/states')->asArray();
$data = array();
foreach ($states as $code => $info) {
    if (isset($info['statuses'])) {
        foreach ($info['statuses'] as $status => $statusInfo) {
            $data[] = array(
                'status'    => $status,
                'state'     => $code,
                'is_default'=> is_array($statusInfo) && isset($statusInfo['@']['default']) ? 1 : 0
            );
        }
    }
}
$installer->getConnection()->insertArray(
    $statusStateTable,
    array('status', 'state', 'is_default'),
    $data
);

$installer->run("
CREATE TABLE `{$statusLabelTable}` (
  `status` varchar(32) NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `label` varchar(128) NOT NULL,
  PRIMARY KEY (`status`,`store_id`),
  KEY `FK_SALES_ORDER_STATUS_LABEL_STORE` (`store_id`),
  CONSTRAINT `FK_SALES_ORDER_STATUS_LABEL_STATUS` FOREIGN KEY (`status`)
    REFERENCES `{$statusTable}` (`status`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SALES_ORDER_STATUS_LABEL_STORE` FOREIGN KEY (`store_id`)
    REFERENCES `{$installer->getTable('core/store')}` (`store_id`)ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");

$installer->getConnection()->addColumn($installer->getTable('sales_flat_invoice'),
    'base_total_refunded', 'decimal(12,4) default NULL');

$installer->getConnection()->addKey($this->getTable('sales/order'), 'IDX_QUOTE_ID' ,'quote_id');
$installer->endSetup();
