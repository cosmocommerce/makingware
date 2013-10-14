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

/**
 * Shipping agreements resource collection
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Model_Mysql4_Shipping_Agreement_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Collection initialization
     *
     */
    protected function _construct()
    {
        $this->_init('sales/shipping_agreement');
    }

    /**
     * Add cutomer details(email, name) to select
     *
     * @return Mage_Sales_Model_Mysql4_Shipping_Agreement_Collection
     */
    public function addCustomerDetails()
    {
        $select = $this->getSelect()->joinInner(
            array('ce' => $this->getTable('customer/entity')),
            'ce.entity_id = main_table.customer_id',
            array('customer_email' => 'email')
        );

        $customer = Mage::getResourceSingleton('customer/customer');

        $attr = $customer->getAttribute('name');
            $select->joinLeft(array('name' => $attr->getBackend()->getTable()),
                'name.entity_id = main_table.customer_id'
                . ' AND name.entity_type_id = ' . $customer->getTypeId()
                . ' AND name.attribute_id = ' . $attr->getAttributeId(),
                array('customer_name' => 'value'));

        return $this;
    }
}
