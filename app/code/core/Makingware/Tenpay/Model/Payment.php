<?php

class Makingware_Tenpay_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'tenpay';
	protected $_formBlockType = 'tenpay/form';
	// Alipay return codes of payment
	const RETURN_CODE_ACCEPTED = 'paiement';
	const RETURN_CODE_TEST_ACCEPTED = 'payetest';
	const RETURN_CODE_ERROR = 'Annulation';
	// Payment configuration
	protected $_isGateway = false;
	protected $_canAuthorize = true;
	protected $_canCapture = true;
	protected $_canCapturePartial = false;
	protected $_canRefund = false;
	protected $_canVoid = false;
	#protected $_canUseInternal = false;
	protected $_canUseCheckout = true;
	protected $_canUseForMultishipping = false;
	// Order instance
	protected $_order = null;

	/**
	 * Returns Target URL
	 *
	 * @return string Target URL
	 */
	public function getTenpayUrl ()
	{
		return 'https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi';
	}

	/**
	 * Return back URL
	 *
	 * @return string URL
	 */
	protected function getReturnURL ()
	{
		return Mage::getUrl('tenpay/payment/normal', array('_secure' => true));
	}

	/**
	 * Return URL for Alipay success response
	 *
	 * @return string URL
	 */
	protected function getSuccessURL ()
	{
		return Mage::getUrl('tenpay/payment/success', array('_secure' => true));
	}

	/**
	 * Return URL for Alipay failure response
	 *
	 * @return string URL
	 */
	protected function getErrorURL ()
	{
		return Mage::getUrl('tenpay/payment/error', array('_secure' => true));
	}

	/**
	 * Return URL for Alipay notify response
	 *
	 * @return string URL
	 */
	protected function getNotifyURL ()
	{
		return Mage::getUrl('tenpay/payment/notify', array('_secure' => false));
	}

	public function getOnlinePaymentUrl ()
	{
		return Mage::getUrl('tenpay/payment/redirect');
	}

	/**
	 * Capture payment
	 *
	 * @param Varien_Object $orderPayment
	 * @return Mage_Payment_Model_Abstract
	 */
	public function capture (Varien_Object $payment, $amount)
	{
		$payment->setStatus(self::STATUS_APPROVED)->setLastTransId(
		$this->getTransactionId());

		return $this;
	}

	/**
	 * Form block description
	 *
	 * @return object
	 */
	public function createFormBlock ($name)
	{
		$block = $this->getLayout()->createBlock('tenpay/form_payment', $name);
		$block->setMethod($this->_code);
		$block->setPayment($this->getPayment());

		return $block;
	}

	/**
	 * Return Order Place Redirect URL
	 *
	 * @return string Order Redirect URL
	 */
	public function getOrderPlaceRedirectUrl ()
	{
		//return Mage::getUrl('tenpay/payment/redirect');
		return ;
	}

	public function getPaymentRedirectUrl()
	{
		return Mage::getUrl('tenpay/payment/redirect');
	}

	/**
	 * Return Standard Checkout Form Fields for request to Alipay
	 *
	 * @return array Array of hidden form fields
	 */
	public function getStandardCheckoutFormFields ()
	{
		$session = Mage::getSingleton('checkout/session');
		$order = $this->getOrder();

		if (! ($order instanceof Mage_Sales_Model_Order)) {
			Mage::throwException($this->_getHelper()->__('Cannot retrieve order object'));
		}

		$billno = $order->getRealOrderId();

		if (strlen($billno) < 10) {
			$billno_plus_rand = $billno . rand(0, 9);
		}else {
			$billno_plus_rand = $billno;
		}

		$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
		$converted_final_price = Mage::helper('directory')->currencyConvert(
		$order->getGrandTotal(), $currency_code, 'CNY');

		if ($converted_final_price) {
			$converted_final_price = $converted_final_price;
		}else {
			$converted_final_price = $order->getGrandTotal();
		}

		$amount = sprintf('%.2f', $converted_final_price) * 100; //the price include tax
		$date = date('Ymd', time());
		$security_code = $this->getConfigData('security_code');
		$attach = 1; //SP's data pakage,it will return from tenpay without any changed.
		$transaction_id = $this->getConfigData('partner_id') . $date .$billno_plus_rand;
		$bargainor_id = $this->getConfigData('partner_id');
		$return_url = $this->getNotifyURL();

		if ($this->getConfigData('debug')) {
			$ip = '';
			$amount = 1;
			$presignstr = 'cmdno=1&date=' . $date . '&bargainor_id=' .
				$bargainor_id . '&transaction_id=' . $transaction_id . '&sp_billno=' .
				$billno . '&total_fee=' . $amount . '&fee_type=1&return_url=' .
				$return_url . '&attach=' . $attach . '&key=' . $security_code;
		}else {
			$ip = $this->getUserIp();
			$presignstr = 'cmdno=1&date=' . $date . '&bargainor_id=' .
				$this->getConfigData('partner_id') . '&transaction_id=' .
				$transaction_id . '&sp_billno=' . $billno . '&total_fee=' . $amount .
				'&fee_type=1&return_url=' . $this->getNotifyURL() . '&attach=' .
				$attach . '&spbill_create_ip=' . $ip . '&key=' . $security_code;
		}

		$sign = strtoupper(md5($presignstr));
		$parameter = array('cmdno' => 1, 'date' => $date,
			'bank_type' => $this->getBankType(),
			'desc' => Mage::getStoreConfig('design/head/default_title') . '-' . $this->_getHelper()->__('Order id is :') . $transaction_id,
			'bargainor_id' => $this->getConfigData('partner_id'),
			'transaction_id' => $transaction_id,
			'sp_billno' => $order->getRealOrderId(),  // order ID
			'total_fee' => $amount, //price
			'fee_type' => 1,  //for now ,tenpay only support RMB. RMB => 1 ,USD => 2, HKD => 3
			'return_url' => $this->getNotifyURL(),
			'attach' => $attach, 'spbill_create_ip' => $ip,  //this is buyer's IP
			'sign' => $sign, 'cs' => 'utf-8'
		);

		$parameter = $this->para_filter($parameter);
		$sort_array = array();
		$arg = '';
		$sort_array = $this->arg_sort($parameter); //$parameter

		while (list ($key, $val) = each($sort_array)) {
			$arg .= $key . '=' . $val . '&';
		}

		$fields = array();
		$sort_array = array();
		$arg = '';
		$sort_array = $this->arg_sort($parameter);

		while (list ($key, $val) = each($sort_array)) {
			$fields[$key] = $val;
		}

		return $fields;
	}

	public function sign ($prestr)
	{
		$mysign = md5($prestr);

		return $mysign;
	}

	public function para_filter ($parameter)
	{
		$para = array();

		while (list ($key, $val) = each($parameter)) {
			$para[$key] = $parameter[$key];
		}

		return $para;
	}

	public function arg_sort ($array)
	{
		ksort($array);
		reset($array);

		return $array;
	}

	public function charset_encode ($input, $_output_charset,  $_input_charset = "GBK")
	{
		return $input;
	}

	/**
	 * Return authorized languages by Tenpay
	 *
	 * @param none
	 * @return array
	 */
	protected function _getAuthorizedLanguages ()
	{
		$languages = array();
		foreach (Mage::getConfig()->getNode(
		'global/payment/tenpay/languages')->asArray() as $data) {
			$languages[$data['code']] = $data['name'];
		}

		return $languages;
	}

	/**
	 * Return language code to send to Tenpay
	 *
	 * @param none
	 * @return String
	 */
	protected function _getLanguageCode ()
	{
		// Store language
		$language = strtoupper(
		substr(Mage::getStoreConfig('general/locale/code'), 0, 2));
		// Authorized Languages
		$authorized_languages = $this->_getAuthorizedLanguages();

		if (count($authorized_languages) === 1) {
			$codes = array_keys($authorized_languages);

			return $codes[0];
		}

		if (array_key_exists($language, $authorized_languages)) {
			return $language;
		}

		// By default we use language selected in store admin
		return $this->getConfigData('language');
	}

	/**
	 * Output failure response and stop the script
	 *
	 * @param none
	 * @return void
	 */
	public function generateErrorResponse ()
	{
		die($this->getErrorResponse());
	}

	/**
	 * Return response for Tenpay success payment
	 *
	 * @param none
	 * @return string Success response string
	 */
	public function getSuccessResponse ()
	{
		$response = array('Pragma: no-cache', 'Content-type : text/plain',
			'Version: 1', 'OK');

		return implode("\n", $response) . "\n";
	}

	/**
	 * Return response for Tenpay failure payment
	 *
	 * @param none
	 * @return string Failure response string
	 */
	public function getErrorResponse ()
	{
		$response = array('Pragma: no-cache', 'Content-type : text/plain',
			'Version: 1', 'Document falsifie');

		return implode("\n", $response) . "\n";
	}

	/**
	 * return user's IP
	 * @param none
	 * return string
	 * Ivon Su 20090316
	 */
	public function getUserIp ()
	{
		if (getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif (getenv("REMOTE_ADDR")) {
			$ip = getenv("REMOTE_ADDR");
		} else {
			$ip = "127.0.0.1";
		}

		return $ip;
	}
	
	public function getBankType()
	{
		$bankType = 0;
		if (Mage::getStoreConfigFlag('payment/tenpay/netsilver_league')) {
			if (($order = $this->getOrder()) && $order instanceof Mage_Sales_Model_Order && $order->getId()) {
				$infoStance = $order->getPayment();
			}else {
				$infoStance = $this->getInfoInstance();
			}
			if ($bankType = $infoStance->getAdditionalInformation('tenpay')) {
				if (is_array($bankType) && $bankType['bank_type']) {
					$bankType = intval($bankType['bank_type']);
				}
			}
		}
		return $bankType;
	}
	
	public function getTitle()
	{
		$bankType = $this->getBankType();
		if (empty($bankType)) {
			return parent::getTitle();
		}
		switch ($bankType) {
			case '1001':
				$bankName = '招商银行借记卡';
				break;
			case '1002':
				$bankName = '中国工商银行';
				break;
			case '1003':
				$bankName = '中国建设银行';
				break;
			case '1004':
				$bankName = '上海浦东发展银行';
				break;
			case '1005':
				$bankName = '中国农业银行';
				break;
			case '1006':
				$bankName = '中国民生银行';
				break;
			case '1008':
				$bankName = '深圳发展银行';
				break;
			case '1009':
				$bankName = '兴业银行';
				break;
			case '1010':
				$bankName = '平安银行';
				break;
			case '1020':
				$bankName = '交通银行';
				break;
			case '1021':
				$bankName = '中信银行';
				break;
			case '1022':
				$bankName = '中国光大银行';
				break;
			case '1024':
				$bankName = '上海银行';
				break;
			case '1025':
				$bankName = '华夏银行';
				break;
			case '1027':
				$bankName = '广东发展银行';
				break;
			case '1028':
				$bankName = '中国邮政储蓄银行（仅支持广东地区）';
				break;
			case '1038':
				$bankName = '招商银行信用卡,招行限额499元';
				break;
			case '1032':
				$bankName = '北京银行';
				break;
			case '1033':
				$bankName = '网汇通';
				break;
			case '1052':
				$bankName = '中国银行';
				break;
			case '8001':
				$bankName = '财付通余额支付';
				break;
		}
		return parent::getTitle() . ' - ' . $bankName;
	}
}