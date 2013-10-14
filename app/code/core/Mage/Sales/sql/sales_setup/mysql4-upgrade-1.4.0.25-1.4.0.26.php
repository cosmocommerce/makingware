<?php
$installer = $this;
$installer->startSetup();

/**
 * 购物车地址和订单地址增加  `mobile` 字段
 */
$installer->getConnection()->addColumn($installer->getTable('sales_flat_quote_address'),
    'mobile', 'varchar(255) default NULL AFTER `telephone`');
$installer->getConnection()->addColumn($installer->getTable('sales_flat_order_address'),
    'mobile', 'varchar(255) default NULL AFTER `telephone`');


/*
* 汉化订单状态 sales_order_status
*/
$statusTable = $installer->getTable('sales/order_status');
$translate = array(
	'canceled'			=> '已取消',
	'closed'			=> '关闭',
	'complete'			=> '完成',
	'fraud'				=> '欺诈订单',
	'holded'			=> '挂起',
	'payment_review'	=> '支付审核',
	'pending'			=> '等待付款',
	'pending_payment'	=> '等待支付',
	'processing'		=> '处理中'
);

$sqles = array();
foreach ($translate as $status => $label) {
	$sqles[] = "UPDATE `{$statusTable}` SET `label` = '{$label}' WHERE `status` = '{$status}'";
}
$installer->run(implode(';', $sqles));

$installer->endSetup();