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

/**
 * One page checkout totals
 *
 * @category   Mage
 * @category   Mage
 * @package    Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Onepage_Totals extends Mage_Checkout_Block_Onepage_Abstract
{
	protected $_totals;
	
	public function getTotals()
    {
        if (empty($this->_totals)) {
            foreach ($this->getQuote()->getTotals() as $total) {
                $this->_totals[$total->getCode()] = $total->getValue();
            }
        }
        return $this->_totals;
    }
    
    public function getSubTotal()
    {
        $totals = $this->getTotals();
        return isset($totals['subtotal']) ? $totals['subtotal'] : '0.00';
    }
    
    public function getShipping()
    {
        $totals = $this->getTotals();
        return isset($totals['shipping']) ? $totals['shipping'] : '0.00';
    }
    
    public function getDiscount()
    {
        $totals = $this->getTotals();
        return isset($totals['discount']) ? $totals['discount'] : '0.00';
    }
    
    public function getGrandTotal()
    {
        $totals = $this->getTotals();
        return $totals['grand_total'];
    }
}
