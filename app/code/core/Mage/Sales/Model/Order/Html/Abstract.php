<?php
abstract class Mage_Sales_Model_Order_Html_Abstract extends Mage_Core_Model_Email_Template_Filter
{
	protected $_template = null;
	
	public function filter($value)
	{
		try {
			foreach (array(
				'/{{foreach\s*(.*?)\s*as\s*(.*?)}}(.*?){{\\/foreach\s*}}/si' => 'foreachDirective',
				'/{{loop\s*(.*?)\s*as\s*(.*?)}}(.*?){{\\/loop\s*}}/si' => 'foreachDirective',
			) as $pattern => $directive) {
				if (preg_match_all($pattern, $value, $constructions, PREG_SET_ORDER)) {
					foreach($constructions as $index => $construction) {
						$replacedValue = '';
						$callback = array($this, $directive);
						if(!is_callable($callback)) {
							continue;
						}
						try {
							$replacedValue = call_user_func($callback, $construction);
						} catch (Exception $e) {
							throw $e;
						}
						$value = str_replace($construction[0], $replacedValue, $value);
					}
				}
			}
		}catch (Exception $e) {
			Mage::logException($e);
		}
		return parent::filter($value);;
	}
	
	public function foreachDirective($construction)
	{
		if (count($this->_templateVars) == 0) {
			return $construction[0];
		}
	
		if ($this->_getVariable($construction[1], '') == '') {
			return '';
		}else {
			$value = '';
			foreach ($this->_getVariable($construction[1]) as $item) {
				$value .= $this->setVariables(array($construction[2] => $item))
					->filter($construction[3]);
			}
			return $value;
		}
	}
	
	public function numberDirective($construction)
    {
    	$params = $this->_getIncludeParameters($construction[2]);
        if (!isset($params['var'])) {
            return '';
        }
        return $params['var'] * 1;
    }
    
    public function priceDirective($construction)
    {
    	$params = $this->_getIncludeParameters($construction[2]);
    	if (!isset($params['var'])) {
    		return '';
    	}
    	return Mage::app()->getStore($this->getStoreId())->formatPrice($params['var']);
    }
    
    public function dateDirective($construction)
    {
    	$params = $this->_getIncludeParameters($construction[2]);
    	if (!isset($params['var'])) {
    		return '';
    	}
    	if (is_numeric($params['var'])) {
    		return date('Y-m-d', $params['var']);
    	}
    	return date('Y-m-d', strtotime($params['var']));
    }
    
    public function addressDirective($construction)
    {
    	$params = $this->_getIncludeParameters($construction[2]);
    	if (!isset($params['var']) || !$params['var'] instanceof Mage_Sales_Model_Order_Address) {
    		return '';
    	}
    	
    	return $params['var']->getRegion() . $params['var']->getCity() . $params['var']->getArea() . 
    		implode(', ', $params['var']->getStreet(null));
    }
    
    public function setTemplate($template)
    {
    	$this->_template = Mage::app()->getTranslator()->getTemplateFile($template, 'sales');
    	return $this;
    }
    
    public function getTemplate()
    {
    	return $this->_template;
    }
}