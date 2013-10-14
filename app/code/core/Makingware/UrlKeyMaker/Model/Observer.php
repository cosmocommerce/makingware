<?php

class Makingware_UrlKeyMaker_Model_Observer
{
    public function changeUrlKey ($observer)
    {
        if (! Mage::getStoreConfig('catalog/makingware_urlkeymaker/active')) {
            return;
        }

        if (! Mage::getStoreConfig('catalog/makingware_urlkeymaker/type')) {
            return;
        }
        
        if (! ($model = $observer->getData('category'))) {
            $model = $observer->getData('product');
        }
        
        if (! $model) {
            return;
        }

        $urlKey = $model->getData('url_key');

        if (! empty($urlKey)) {
            return;
        }

        $type = Mage::getStoreConfig('catalog/makingware_urlkeymaker/type');

        switch ($type) {
            case 'sku':
                $urlKey = $this->_makeSkuKey($model);
                break;
            case 'pinyin':
                $urlKey = $this->_makePinyinKey($model);
                break;
            default:
                $urlKey = '';
        }
        
        if (! empty($urlKey)) {
            $model->setData('url_key', $urlKey);
        }
    }

    protected function _makeSkuKey ($model)
    {
        $sku = $model->getData('sku');

        if (empty($sku)) {
            $result = '';
        } else {
            $result = $model->getData('sku');
        }

        return $result;
    }

    protected function _makePinyinKey ($model)
    {
        $name = $model->getData('name');

        if (empty($name)) {
            $result = '';
        } else {
            $separator = Mage::getStoreConfig('catalog/makingware_urlkeymaker/separator');
            $result = Mage::helper('makingware_urlkeymaker')->convert($name, $separator);
        }
        return $result;
    }
}
