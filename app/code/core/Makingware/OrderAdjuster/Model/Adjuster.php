<?php
class Makingware_OrderAdjuster_Model_Adjuster extends Mage_Core_Model_Abstract
{
	static $editor_fields = array(
		'base_adjuster_amount' => 0,
		'adjuster_amount' => 0
	);

	protected $_eventPrefix = 'makingware_orderadjuster_adjuster';

	protected $_order = null;

	protected $_collectTotals = false;

	protected function _construct()
    {
        $this->_init('makingware_orderadjuster/adjuster');
    }

    public function setOrder(Mage_Sales_Model_Order $order)
    {
    	$this->_order = $order;

    	if ($order->getId()) {
    		$this->setOrderId($order->getId());
    	}

    	if (! $this->getId() && $this->getOrderId()) {
    		$this->load($this->getOrderId(), 'order_id');
    	}

    	return $this;
    }

    public function getOrder()
    {
    	if (is_null($this->_order)) {
    		$order = Mage::getSingleton('sales/order');
    		if (! $order->getId() && $this->getOrderId()) {
    			$order->load($this->getOrderId());
    		}
    		$this->setOrder($order);
    	}

    	return $this->_order;
    }

    public function getEditorFields()
    {
    	return self::$editor_fields;
    }

    public function getEditorData()
    {
    	if ($this->getId()) {
    		return array_intersect_key($this->_data, self::$editor_fields);
    	}

    	return self::$editor_fields;
    }

    public function canOrderEditor()
    {
    	$order = $this->getOrder();

    	if (! $order->getId()) {
    		return false;
    	}

    	if ($order->canUnhold()) {
    		return false;
	    }

	    $state = $order->getState();
	    if ($order->isCanceled() || $order->isPaymentReview()
	    	|| $state === Mage_Sales_Model_Order::STATE_COMPLETE || $state === Mage_Sales_Model_Order::STATE_CLOSED) {
	    	return false;
	    }

	    if (!$order->getPayment()->getMethodInstance()->canEdit()) {
	        return false;
	    }

	    if ($order->getTotalInvoiced() > 0 && $order->getTotalInvoiced() >= $order->getGrandTotal()) {
	    	return false;
	    }

	    if ($order->getActionFlag(Mage_Sales_Model_Order::ACTION_FLAG_EDIT) === false) {
	        return false;
	    }

	    return true;
    }

    public function collectTotals()
    {
    	if (! $this->_collectTotals && $this->canOrderEditor()) {
    		$this->_collectTotals = true;

    		$order = $this->getOrder();
            $grandTotal = $order->getBaseGrandTotal();
         
    		foreach ($this->getEditorData() as $field => $value) {
    			if(substr($field,0,4)=='base'){
					 $grandTotal += $value;
    			}
    		}
            $order->setGrandTotal($grandTotal);
            #$order->setBaseGrandTotal($grandTotal);
    	}

    	return $this;
    }

    protected function _afterSave()
    {
    	if (! $this->_collectTotals) {
    		$this->collectTotals();
    	}

    	if ($this->_collectTotals) {
    		$this->getOrder()->save();
    	}

    	return parent::_afterSave();
    }
}
