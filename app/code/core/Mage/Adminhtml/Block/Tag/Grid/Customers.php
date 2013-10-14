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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml tagginf customers grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Tag_Grid_Customers extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        //TODO: add full name logic
        $collection = Mage::getResourceModel('tag_customer/collection')
            ->addAttributeToSelect('name');

        if ($productId = $this->getRequest()->getParam('product_id')) {
            $collection->addProductFilter($productId);
        }
        if ($tagId = $this->getRequest()->getParam('tag_id')) {
            $collection->addTagFilter($tagId);
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    =>Mage::helper('tag')->__('ID'),
            'width'     => '40px',
            'align'     =>'center',
            'sortable'  =>true,
            'index'     =>'entity_id'
        ));
        $this->addColumn('name', array(
            'header'    =>Mage::helper('tag')->__('Name'),
            'index'     =>'name'
        ));
        $this->addColumn('tags', array(
            'header'    => Mage::helper('tag')->__('Tags'),
            'index'     => 'tags',
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => 'adminhtml/tag_grid_column_renderer_tags'
        ));
        $this->addColumn('action', array(
            'header'    =>Mage::helper('tag')->__('Action'),
            'align'     =>'center',
            'width'     => '120px',
            'format'    =>'<a href="'.$this->getUrl('*/*/products/customer_id/$entity_id').'">'.Mage::helper('tag')->__('View Products').'</a>',
            'filter'    =>false,
            'sortable'  =>false,
            'is_system' =>true
        ));

        $this->setColumnFilter('entity_id')
            ->setColumnFilter('email')
            ->setColumnFilter('name');

//        $this->addExportType('*/*/exportCsv', Mage::helper('tag')->__('CSV'));
//        $this->addExportType('*/*/exportXml', Mage::helper('tag')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection() && $column->getFilter()->getValue()) {
            $this->getCollection()->addAttributeToFilter($column->getIndex(), $column->getFilter()->getCondition());
        }
        return $this;
    }

}
