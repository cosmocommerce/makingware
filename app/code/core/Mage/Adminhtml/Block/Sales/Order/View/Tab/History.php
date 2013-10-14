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
 * Order history tab
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Sales_Order_View_Tab_History
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sales/order/view/tab/history.phtml');
    }

    /**
     * Retrieve order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }
    
    public function getUser($userId)
    {
    	static $_user = array();
    	if (false == isset($_user[$userId])) {
    		$_user[$userId] = Mage::getModel('admin/user')->load($userId);
    	}
    	return $_user[$userId];
    }

    /**
     * Compose and get order full history.
     * Consists of the status history comments as well as of invoices, shipments and creditmemos creations
     * @return array
     */
    public function getFullHistory()
    {
        $order = $this->getOrder();

        $history = array();
        foreach ($order->getAllStatusHistory() as $orderComment){
            $history[$orderComment->getEntityId()] = $this->_prepareHistoryItem(
                $orderComment->getStatusLabel(),
                $orderComment->getIsCustomerNotified(),
                $orderComment->getCreatedAtDate(),
                $orderComment->getComment(),
                $orderComment->getUserId() ? $orderComment->getName() . ' [' . $orderComment->getUsername() . ']' : ''
            );
        }

        foreach ($order->getCreditmemosCollection() as $_memo){
       		$userInfo = '';
            if (($userId = $_memo->getUserId()) && ($user = $this->getUser($userId)) && $user->getId()) {
            	$userInfo = $user->getName() . ' [' . $user->getUsername() . ']';
            }
            
            $history[$_memo->getEntityId()] =
                $this->_prepareHistoryItem($this->__('Credit memo #%s created', $_memo->getIncrementId()),
                    $_memo->getEmailSent(), $_memo->getCreatedAtDate(), '', $userInfo);

            foreach ($_memo->getCommentsCollection() as $_comment){
                $history[$_comment->getEntityId()] =
                    $this->_prepareHistoryItem(
                    	$this->__('Credit memo #%s comment added', $_memo->getIncrementId()),
                        $_comment->getIsCustomerNotified(), 
                        $_comment->getCreatedAtDate(), 
                        $_comment->getComment(),
                        $_comment->getUserId() ? $_comment->getName() . ' [' . $_comment->getUsername() . ']' : ''
                    );
            }
        }

        foreach ($order->getShipmentsCollection() as $_shipment){
        	$userInfo = '';
            if (($userId = $_shipment->getUserId()) && ($user = $this->getUser($userId)) && $user->getId()) {
            	$userInfo = $user->getName() . ' [' . $user->getUsername() . ']';
            }
            
            $history[$_shipment->getEntityId()] =
                $this->_prepareHistoryItem($this->__('Shipment #%s created', $_shipment->getIncrementId()),
                    $_shipment->getEmailSent(), $_shipment->getCreatedAtDate(), '', $userInfo);
            

            foreach ($_shipment->getCommentsCollection() as $_comment){
                $history[$_comment->getEntityId()] =
                    $this->_prepareHistoryItem(
                    	$this->__('Shipment #%s comment added', $_shipment->getIncrementId()),
                        $_comment->getIsCustomerNotified(), 
                        $_comment->getCreatedAtDate(), 
                        $_comment->getComment(), 
                        $_comment->getUserId() ? $_comment->getName() . ' [' . $_comment->getUsername() . ']' : ''
                    );
            }
        }

        foreach ($order->getInvoiceCollection() as $_invoice){
        	$userInfo = '';
            if (($userId = $_invoice->getUserId()) && ($user = $this->getUser($userId)) && $user->getId()) {
            	$userInfo = $user->getName() . ' [' . $user->getUsername() . ']';
            }
            
            $history[$_invoice->getEntityId()] =
                $this->_prepareHistoryItem($this->__('Invoice #%s created', $_invoice->getIncrementId()),
                    $_invoice->getEmailSent(), $_invoice->getCreatedAtDate(), '', $userInfo);

            foreach ($_invoice->getCommentsCollection() as $_comment){
                $history[$_comment->getEntityId()] =
                    $this->_prepareHistoryItem(
                    	$this->__('Invoice #%s comment added', $_invoice->getIncrementId()),
                        $_comment->getIsCustomerNotified(), 
                        $_comment->getCreatedAtDate(), 
                        $_comment->getComment(),
                        $_comment->getUserId() ? $_comment->getName() . ' [' . $_comment->getUsername() . ']' : ''
                    );
            }
        }

        foreach ($order->getTracksCollection() as $_track){
            $history[$_track->getEntityId()] =
                $this->_prepareHistoryItem($this->__('Tracking number %s for %s assigned', $_track->getNumber(), $_track->getTitle()),
                    false, $_track->getCreatedAtDate());
        }

        krsort($history);
        return $history;
    }

    /**
     * Status history date/datetime getter
     * @param array $item
     * @return string
     */
    public function getItemCreatedAt(array $item, $dateType = 'date', $format = 'medium')
    {
        if (!isset($item['created_at'])) {
            return '';
        }
        if ('date' === $dateType) {
            return $this->helper('core')->formatDate($item['created_at'], $format);
        }
        return $this->helper('core')->formatTime($item['created_at'], $format);
    }

    /**
     * Status history item title getter
     * @param array $item
     * @return string
     */
    public function getItemTitle(array $item)
    {
        return (isset($item['title']) ? $this->escapeHtml($item['title']) : '');
    }

    /**
     * Check whether status history comment is with customer notification
     * @param array $item
     * @return bool
     */
    public function isItemNotified(array $item, $isSimpleCheck = true)
    {
        if ($isSimpleCheck) {
            return !empty($item['notified']);
        }
        return isset($item['notified']) && false !== $item['notified'];
    }

    /**
     * Status history item comment getter
     * @param array $item
     * @return string
     */
    public function getItemComment(array $item)
    {
        $allowedTags = array('b','br','strong','i','u');
        return (isset($item['comment']) ? $this->escapeHtml($item['comment'], $allowedTags) : '');
    }
    
 	/**
     * Status history item user_info getter
     * @param array $item
     * @return string
     */
    public function getItemUserInfo(array $item)
    {
        $allowedTags = array('b','br','strong','i','u');
        return (isset($item['user_info']) ? $this->escapeHtml($item['user_info'], $allowedTags) : '');
    }

    /**
     * Map history items as array
     * @param string $label
     * @param bool $notified
     * @param Zend_Date $created
     * @param string $comment
     */
    protected function _prepareHistoryItem($label, $notified, $created, $comment = '', $userInfo = '')
    {
        return array(
            'title'      => $label,
            'notified'   => $notified,
            'created_at' => $created,
        	'comment'    => $comment,
        	'user_info'	 => $userInfo
        );
    }

    /**
     * ######################## TAB settings #################################
     */
    public function getTabLabel()
    {
        return Mage::helper('sales')->__('Comments History');
    }

    public function getTabTitle()
    {
        return Mage::helper('sales')->__('Order History');
    }

    public function getTabClass()
    {
        return 'ajax only';
    }

    public function getClass()
    {
        return $this->getTabClass();
    }

    public function getTabUrl()
    {
        return $this->getUrl('*/*/commentsHistory', array('_current' => true));
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
