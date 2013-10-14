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
 * @package     Mage_Reports
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Reports Viewed Product Index Resource Model
 *
 * @category   Mage
 * @package    Mage_Reports
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Reports_Model_Mysql4_Product_Index_Viewed extends Mage_Reports_Model_Mysql4_Product_Index_Abstract
{
    /**
     * Initialize connection and main resource table
     *
     */
    protected function _construct()
    {
        $this->_init('reports/viewed_product_index', 'index_id');
    }
	
	public function  cleanRecentView()
	{
		if (Mage::getSingleton('customer/session')->isLoggedIn())
		{
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$customerId = $customer->getId();
		}
		else
		{
			$subjectId = Mage::getSingleton('log/visitor')->getId();
		}
		if(!empty($customerId))
		{
			$select = $this->_getReadAdapter()->select()
			->from(array('main_table' => $this->getMainTable()), array($this->getIdFieldName()))
			->joinLeft(
				array('visitor_table' => $this->getTable('log/visitor')),
				'main_table.visitor_id = visitor_table.visitor_id',
				array())
			->where('main_table.customer_id ='.$customerId);
		}
		else
		{

			$select = $this->_getReadAdapter()->select()
			->from(array('main_table' => $this->getMainTable()), array($this->getIdFieldName()))
			->joinLeft(
				array('visitor_table' => $this->getTable('log/visitor')),
				'main_table.visitor_id = visitor_table.visitor_id',
				array())
			->where('main_table.visitor_id ='.$subjectId);
		}
		$indexIds = $this->_getReadAdapter()->fetchCol($select);
		if (!$indexIds) {
			return ;
		}

		$this->_getWriteAdapter()->delete(
			$this->getMainTable(),
			$this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' IN(?)', $indexIds)
		);
	}
}
