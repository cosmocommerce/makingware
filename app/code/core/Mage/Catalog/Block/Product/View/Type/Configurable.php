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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog super product configurable part block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Block_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Abstract
{
    protected $_prices      = array();
    protected $_resPrices   = array();

    public function getAllowAttributes()
    {
        return $this->getProduct()->getTypeInstance(true)
            ->getConfigurableAttributes($this->getProduct());
    }

    public function hasOptions()
    {
        $attributes = $this->getAllowAttributes();
        if (count($attributes)) {
            foreach ($attributes as $key => $attribute) {
                /** @var Mage_Catalog_Model_Product_Type_Configurable_Attribute $attribute */
                if ($attribute->getData('prices')) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getAllowProducts()
    {
        if (!$this->hasAllowProducts()) {
            $products = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {
                if ($product->isSaleable()) {
                    $products[] = $product;
                }
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }

    /**
     * retrieve current store
     *
     * @return Mage_Core_Model_Store
     */
    public function getCurrentStore()
    {
        return Mage::app()->getStore();
    }

    /**
     * Returns additional values for js config, con be overriden by descedants
     *
     * @return array
     */
    protected function _getAdditionalConfig()
    {
        return array();
    }

    /**
     * Composes configuration for js
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $attributes = array();
        $options    = array();
        $store      = $this->getCurrentStore();
        $currentProduct = $this->getProduct();

        $preconfiguredFlag = $currentProduct->hasPreconfiguredValues();
        if ($preconfiguredFlag) {
            $preconfiguredValues = $currentProduct->getPreconfiguredValues();
            $defaultValues       = array();
        }

        foreach ($this->getAllowProducts() as $product) {
            $productId  = $product->getId();

            foreach ($this->getAllowAttributes() as $attribute) {
                $productAttribute   = $attribute->getProductAttribute();
                $productAttributeId = $productAttribute->getId();
                $attributeValue     = $product->getData($productAttribute->getAttributeCode());
                if (!isset($options[$productAttributeId])) {
                    $options[$productAttributeId] = array();
                }

                if (!isset($options[$productAttributeId][$attributeValue])) {
                    $options[$productAttributeId][$attributeValue] = array();
                }
                $options[$productAttributeId][$attributeValue][] = $productId;
            }
        }

        $this->_resPrices = array(
            $this->_preparePrice($currentProduct->getFinalPrice())
        );

        foreach ($this->getAllowAttributes() as $attribute) {
            $productAttribute = $attribute->getProductAttribute();
            $attributeId = $productAttribute->getId();
            $info = array(
               'id'        => $productAttribute->getId(),
               'code'      => $productAttribute->getAttributeCode(),
               'label'     => $attribute->getLabel(),
               'options'   => array()
            );

            $optionPrices = array();
            $prices = $attribute->getPrices();
            if (is_array($prices)) {
                foreach ($prices as $value) {
                    if(!$this->_validateAttributeValue($attributeId, $value, $options)) {
                        continue;
                    }
                    $currentProduct->setConfigurablePrice($this->_preparePrice($value['pricing_value'], $value['is_percent']));
                    Mage::dispatchEvent(
                        'catalog_product_type_configurable_price',
                        array('product' => $currentProduct)
                    );
                    $configurablePrice = $currentProduct->getConfigurablePrice();

                    $info['options'][] = array(
                        'id'        => $value['value_index'],
                        'label'     => $value['label'],
                        'price'     => $configurablePrice,
                        'oldPrice'  => $this->_preparePrice($value['pricing_value'], $value['is_percent']),
                        'products'  => isset($options[$attributeId][$value['value_index']]) ? $options[$attributeId][$value['value_index']] : array(),
                    );
                    $optionPrices[] = $configurablePrice;
                    //$this->_registerAdditionalJsPrice($value['pricing_value'], $value['is_percent']);
                }
            }
            /**
             * Prepare formated values for options choose
             */
            foreach ($optionPrices as $optionPrice) {
                foreach ($optionPrices as $additional) {
                    $this->_preparePrice(abs($additional-$optionPrice));
                }
            }
            if($this->_validateAttributeInfo($info)) {
               $attributes[$attributeId] = $info;
            }

            // Add attribute default value (if set)
            if ($preconfiguredFlag) {
                $configValue = $preconfiguredValues->getData('super_attribute/' . $attributeId);
                if ($configValue) {
                    $defaultValues[$attributeId] = $configValue;
                }
            }
        }

        $config = array(
            'attributes'        => $attributes,
            'template'          => str_replace('%s', '#{price}', $store->getCurrentCurrency()->getOutputFormat()),
//            'prices'          => $this->_prices,
            'basePrice'         => $this->_registerJsPrice($this->_convertPrice($currentProduct->getFinalPrice())),
            'oldPrice'          => $this->_registerJsPrice($this->_convertPrice($currentProduct->getPrice())),
            'productId'         => $currentProduct->getId(),
            'chooseText'        => Mage::helper('catalog')->__('Choose an Option...')
        );

        if ($preconfiguredFlag && !empty($defaultValues)) {
            $config['defaultValues'] = $defaultValues;
        }

        $config = array_merge($config, $this->_getAdditionalConfig());

        return Mage::helper('core')->jsonEncode($config);
    }

    /**
     * Validating of super product option value
     *
     * @param array $attribute
     * @param array $value
     * @param array $options
     * @return boolean
     */
    protected function _validateAttributeValue($attributeId, &$value, &$options)
    {
        if(isset($options[$attributeId][$value['value_index']])) {
            return true;
        }

        return false;
    }

    /**
     * Validation of super product option
     *
     * @param array $info
     * @return boolean
     */
    protected function _validateAttributeInfo(&$info)
    {
        if(count($info['options']) > 0) {
            return true;
        }
        return false;
    }

    protected function _preparePrice($price, $isPercent=false)
    {
        if ($isPercent && !empty($price)) {
            $price = $this->getProduct()->getPrice()*$price/100;
        }

        return $this->_registerJsPrice($this->_convertPrice($price, true));
    }

    protected function _registerJsPrice($price)
    {
        $jsPrice            = str_replace(',', '.', $price);

//        if (!isset($this->_prices[$jsPrice])) {
//            $this->_prices[$jsPrice] = strip_tags(Mage::app()->getStore()->formatPrice($price));
//        }
        return $jsPrice;
    }

    protected function _convertPrice($price, $round=false)
    {
        if (empty($price)) {
            return 0;
        }

        $price = $this->getCurrentStore()->convertPrice($price);
        if ($round) {
            $price = $this->getCurrentStore()->roundPrice($price);
        }


        return $price;
    }

    public function getColorJson()
    {
		 $config = array(
            'attributes'        => $this->getColorAttributes(),
            'basePrice'         => $this->_registerJsPrice($this->_convertPrice($this->getProduct()->getFinalPrice())),
            'oldPrice'         => $this->_registerJsPrice($this->_convertPrice($this->getProduct()->getPrice())),
            'productId'         => $this->getProduct()->getId(),
            'colors'            =>$this->getAllColors(),
            'sizes'          =>$this->getAllSizes()
        );
        
        return Mage::helper('core')->jsonEncode($config);
    }

    public function getColorAttributes()
    {
        $currentProduct = $this->getProduct();
		$allProducts=array();

		foreach ($this->getAllProducts() as $product)
		{
			$allProducts[$product->getId()]['stock']=$product->isSaleable();
            $allProducts[$product->getId()]['image']['image']=$product->getImage();
		    $allProducts[$product->getId()]['image']['small_image']=$product->getSmallImage();
			$allProducts[$product->getId()]['image']['thumbnail']=$product->getThumbnail();

            foreach ($this->getAllowAttributes() as $attribute)
            {
			   $productAttribute = $attribute->getProductAttribute();
			   $attributeValue = $product->getData($productAttribute->getAttributeCode());

			   $prices = $attribute->getPrices();

				if (is_array($prices))
				{
					foreach ($prices as $value)
					{   
						if($attributeValue==$value['value_index'])
						{
							if($productAttribute->getFrontendInput()=='color')
							{
								$allProducts[$product->getId()]['color']['option_id']=$value['value_index'];
							    $allProducts[$product->getId()]['color']['option_value']=$value['label'];
                                $currentProduct->setConfigurablePrice($this->_preparePrice($value['pricing_value'], $value['is_percent']));
                                Mage::dispatchEvent(
                                    'catalog_product_type_configurable_price',
                                    array('product' => $currentProduct)
                                );
                                $configurablePrice = $currentProduct->getConfigurablePrice();
                                $allProducts[$product->getId()]['color']['price']=$configurablePrice;
                                $allProducts[$product->getId()]['color']['oldPrice']= $this->_preparePrice($value['pricing_value'], $value['is_percent']);
							}
							else if($productAttribute->getFrontendInput()=='size')
							{
                            	$allProducts[$product->getId()]['size']['option_id']=$value['value_index'];
							    $allProducts[$product->getId()]['size']['option_value']=$value['label'];
                                $currentProduct->setConfigurablePrice($this->_preparePrice($value['pricing_value'], $value['is_percent']));
                                Mage::dispatchEvent(
                                    'catalog_product_type_configurable_price',
                                    array('product' => $currentProduct)
                                );
                                $configurablePrice = $currentProduct->getConfigurablePrice();
                                $allProducts[$product->getId()]['size']['price']=$configurablePrice;
                                $allProducts[$product->getId()]['size']['oldPrice']= $this->_preparePrice($value['pricing_value'], $value['is_percent']);
							}
						}
					}
				}
            }
            
            $productPrice=$allProducts[$product->getId()]['color']['price']+$allProducts[$product->getId()]['size']['price']+$currentProduct->getFinalPrice();   
            $productOldPrice=$allProducts[$product->getId()]['color']['oldPrice']+$allProducts[$product->getId()]['size']['oldPrice']+$currentProduct->getPrice();    
            $allProducts[$product->getId()]['price']=$this->_registerJsPrice($this->_convertPrice($productPrice));
            $allProducts[$product->getId()]['oldPrice']=$this->_registerJsPrice($this->_convertPrice($productOldPrice));   
		}

		return $allProducts;
    }

    public function getAllColors()
    {
		$allColors=array();

		foreach ($this->getAllProducts() as $product)
		{
            foreach ($this->getAllowAttributes() as $attribute)
            {
			   $productAttribute = $attribute->getProductAttribute();
			   $attributeValue = $product->getData($productAttribute->getAttributeCode());
			   $prices = $attribute->getPrices();

				if (is_array($prices)){
					foreach ($prices as $value){
						if($attributeValue==$value['value_index']){
							if($productAttribute->getFrontendInput()=='color'){   
								if(!empty($value['color_value'])){
									$allColors[$value['value_index']]['colorValue']=$value['color_value'];
								}
                                
                                if(!empty($value['color_pic'])){
                                     $allColors[$value['value_index']]['imageUrl']=$value['color_pic'];   
                                    
                                }else{
                                   $allColors[$value['value_index']]['imageUrl']=$value['image_url']?$value['image_url']:'';
                                }
                                
                                if(!empty($value['color_text'])){
                                    $allColors[$value['value_index']]['colorLabel']=$value['color_text'];
                                }else{
                                    $allColors[$value['value_index']]['colorLabel']=$value['label'];  
                                }

							}
						}
					}
				}
            }
		}
       // print_r($allColors);die;
		return $allColors;
    }

    public function getAllsizes()
    {
		$allSizes=array();

		foreach ($this->getAllProducts() as $product)
		{
            foreach ($this->getAllowAttributes() as $attribute)
            {
			   $productAttribute = $attribute->getProductAttribute();
			   $attributeValue = $product->getData($productAttribute->getAttributeCode());
			   $prices = $attribute->getPrices();

				if (is_array($prices))
				{
					foreach ($prices as $value)
					{
						if($attributeValue==$value['value_index'])
						{
							if($productAttribute->getFrontendInput()=='size')
							{
								$allSizes[$value['value_index']]['sizeValue']=$value['label'];
							}
						}
					}
				}
            }
		}

		return $allSizes;
    }

    public function getAllProducts()
    {
        if (!$this->hasAllProducts()) {
            $products = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {
                    $products[] = $product;
            }
            $this->setAllProducts($products);
        }

        return $this->getData('all_products');
    }

    public function getImageUrl()
    {
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product';
    }

    public function getPostUrl ()
    {
        return Mage::getUrl('catalog/product/getProductMedia', array('_secure' => true));
    }

//    protected function _registerAdditionalJsPrice($price, $isPercent=false)
//    {
//        if (empty($price) && isset($this->_prices[0])) {
//            return $this;
//        }
//
//        $basePrice = $this->getProduct()->getFinalPrice();
//        if ($isPercent) {
//            $price = $basePrice*$price/100;
//        }
//        else {
//            $price = $price;
//        }
//
//        $price = $this->_convertPrice($price);
//
//        foreach ($this->_resPrices as $prevPrice) {
//        	$additionalPrice = $prevPrice + $price;
//        	$this->_resPrices[] = $additionalPrice;
//        	$jsAdditionalPrice = str_replace(',', '.', $additionalPrice);
//        	$this->_prices[$jsAdditionalPrice] = strip_tags(Mage::app()->getStore()->formatPrice($additionalPrice));
//        }
//        return $this;
//    }
}
