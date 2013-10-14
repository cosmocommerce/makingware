<?php
$installer = $this;
$installer->startSetup();

/*
* 汉化客户分组 customer_group
*/
$customerGroupTable = $installer->getTable('customer/customer_group');
$translate = array(
	'NOT LOGGED IN'		=> '游客',
	'General'			=> '会员',
	'Wholesale'			=> '批发商',
	'Retailer'			=> '零售商',
);

$sqles = array();
foreach ($translate as $code => $label) {
	$sqles[] = "UPDATE `{$customerGroupTable}` SET `customer_group_code` = '{$label}' WHERE `customer_group_code` = '{$code}'";
}
$installer->run(implode(';', $sqles));

$installer->endSetup();