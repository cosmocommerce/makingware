<?php
	/*
	*功能：输出类
	*版本：2.0
	*日期：2008-01-05
	*作者：支付宝公司销售部技术支持团队
	*联系：0571-26888888
	*版权：支付宝公司
	*/

class Makingware_Alipay_Model_Notify extends Mage_Payment_Model_Method_Abstract{
	var $gateway;           //支付接口
	var $security_code;  	//安全校验码
	var $partner;           //合作伙伴ID
	var $sign_type;         //加密方式 系统默认
	var $mysign;            //签名
	var $_input_charset;    //字符编码格式
	var $transport;         //访问模式

	function confirm(){
	 //$_GET = $_GET;
	 $id    = Mage::getStoreConfig('payment/alipay_payment/partner_id');
	 $code  = Mage::getStoreConfig('payment/alipay_payment/security_code');
	 $this->alipay_notify($id,$code,"MD5","GBK","http");
	 $verify_result = $this->return_verify();
	 return $verify_result;
  }


	function alipay_notify($partner,$security_code,$sign_type = "MD5",$_input_charset = "GBK",$transport= "https") {
		$this->partner        = $partner;
		$this->security_code  = $security_code;
		$this->sign_type      = $sign_type;
		$this->mysign         = "";
		$this->_input_charset = $_input_charset ;
		$this->transport      = $transport;
		if($this->transport == "https") {
			$this->gateway = "https://www.alipay.com/cooperate/gateway.do?";
		}else $this->gateway = "http://notify.alipay.com/trade/notify_query.do?";
	}
/****************************************对notify_url的认证*********************************/
	function notify_verify() {
		if($this->transport == "https") {
			$veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_POST["notify_id"];
		} else {
			$veryfy_url = $this->gateway. "notify_id=".$_POST["notify_id"]."&partner=" .$this->partner;
		}
        Mage::log('校验地址是:'.$veryfy_url);
		$veryfy_result = $this->get_verify($veryfy_url);
        Mage::log('成功获取校验内容');
		$post          = $this->para_filter($_POST);
        Mage::log('处理提交资料');
		$sort_post     = $this->arg_sort($post);
		$arg           = '';
		while (list ($key, $val) = each ($sort_post)) {
			$arg .=$key."=".$val."&";
		}
		$prestr = substr($arg,0,count($arg)-2);  //去掉最后一个&号
        Mage::log('计算校验签名:'.$prestr.$this->security_code);
		$this->mysign = $this->sign($prestr.$this->security_code);
        Mage::log('开始比较校验签名');
		if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_POST["sign"])  {
            Mage::log('签名正确');
			return true;
		} else {
            Mage::log('签名错误');
            return false;
        }
	}
/*******************************************************************************************/

/**********************************对return_url的认证***************************************/
	function return_verify() {
    if($this->transport == "https") {
			$veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_GET["notify_id"];
		} else {
			$veryfy_url = $this->gateway. "notify_id=".$_GET["notify_id"]."&partner=" .$this->partner;
		}
		$veryfy_result = $this->get_verify($veryfy_url);
		$GET           = $this->para_filter($_GET);
		$sort_get      = $this->arg_sort($_GET);
		$arg           = '';
		while (list ($key, $val) = each ($sort_get)) {
			if($key != "sign" && $key != "sign_type")
			$arg .=$key."=".$val."&";
		}
		//exit(var_dump($arg));
		$prestr = substr($arg,0,count($arg)-2);  //去掉最后一个&号
		$this->mysign = $this->sign($prestr.$this->security_code);
		if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_GET["sign"])  {
			return true;
		}else return false;
	}
/*******************************************************************************************/

	function get_verify($url,$time_out = "60") {
		$urlarr     = parse_url($url);
		$errno      = "";
		$errstr     = "Error during the call";
		$transports = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$kmx_url = $transports . $urlarr['host'];
        Mage::log('校验请求地址:'.$kmx_url);
    //exit(var_dump($kmx_url . ':'.$urlarr['port'].':'.$errno.':'.$errstr.':'.$time_out));
		$fp=@fsockopen($kmx_url,$urlarr['port'],$errno,$errstr,$time_out);
		if(!$fp) {
            Mage::log('不能连接校验地址');
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr["query"] . "\r\n\r\n");
            $info = array();
			while(!feof($fp)) {
				$info[]=@fgets($fp, 1024);
			}
			fclose($fp);
			$info = implode(",",$info);
            Mage::log('获取校验信息:'.$info);
			//log_result("return_url_log=".$url.$this->charset_decode($info,$this->_input_charset));
			//log_result("return_url_log=".$this->charset_decode($arg,$this->_input_charset));
			return $info;
		}
	}

	function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;
	}

	function sign($prestr) {
		$sign='';
		if($this->sign_type == 'MD5') {
			$sign = md5($prestr);
		}elseif($this->sign_type =='DSA') {
			//DSA 签名方法待后续开发
			die("DSA 签名方法待后续开发，请先使用MD5签名方式");
		}else {
			die("支付宝暂不支持".$this->sign_type."类型的签名方式");
		}
		return $sign;
	}
/***********************除去数组中的空值和签名模式*****************************/
	function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para[$key] = $parameter[$key];
		}
		return $para;
	}
/********************************************************************************/

/******************************实现多种字符编码方式*****************************/
	function charset_encode($input,$_output_charset ,$_input_charset ="GBK" ) {
		$output = "";
		if(!isset($_output_charset) )$_output_charset  = $this->parameter['_input_charset'];
		if($_input_charset == $_output_charset || $input ==null ) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}
/********************************************************************************/

/******************************实现多种字符解码方式******************************/
	function charset_decode($input,$_input_charset ,$_output_charset="GBK"  ) {
		$output = "";
		if(!isset($_input_charset) )$_input_charset  = $this->_input_charset ;
		if($_input_charset == $_output_charset || $input ==null ) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset changes.");
		return $output;
	}
/*********************************************************************************/
}
?>
