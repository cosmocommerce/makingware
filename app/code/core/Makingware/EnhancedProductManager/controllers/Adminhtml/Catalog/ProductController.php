<?php

include_once "Mage/Adminhtml/controllers/Catalog/ProductController.php";

class Makingware_EnhancedProductManager_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    protected $massactionEventDispatchEnabled = true;

    protected function _construct ()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Makingware_EnhancedProductManager');
    }

    /**
     * Product list page
     */
    public function indexAction ()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog/enhancedproductmanager');
        $this->renderLayout();
    }

    /**
     * Product grid for AJAX request
     */
    public function gridAction ()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()
            ->createBlock(
        		'makingware_enhancedproductmanager/catalog_product_grid')
            ->toHtml()
        );
    }

    public function inlineAction ()
    {
        $id = $this->getRequest()->getParam('id');
        $attribute = $this->getRequest()->getParam('attribute');
        $value = $this->getRequest()->getParam('value');

        switch ($attribute) {
            case 'price':
                $value = preg_replace('/[^0-9.]/', '', $value);
                break;
        }

        $product = Mage::getModel('catalog/product');
        /* @var $product Mage_Catalog_Model_Product */
        $product->load($id);
        $product->setData($attribute, $value);
        $product->save();

        switch ($attribute) {
            case 'price':
                $currency = Mage::app()->getStore()->getCurrentCurrencyCode();
                $value = Mage::app()->getLocale()->currency($currency)->toCurrency($value);
                break;
            case 'status':
                $value = $product->getAttributeText('status');
                break;
        }

        $this->getResponse()->setBody($value);
    }

    protected function _isAllowed ()
    {
        return Mage::getSingleton('admin/session')->isAllowed(
        'catalog/products');
    }

    /**
     * Export product grid to CSV format
     */
    public function exportCsvAction ()
    {
        $fileName = 'products.csv';
        $content = $this->getLayout()
            ->createBlock('makingware_enhancedproductmanager/catalog_product_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * Export product grid to XML format
     */
    public function exportXmlAction ()
    {
        $fileName = 'products.xml';
        $content = $this->getLayout()
            ->createBlock('makingware_enhancedproductmanager/catalog_product_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse ($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control',
        'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition',
        'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die();
    }

    /**
     * This will relate all products selected to each other.
     *
     */
    public function massRefreshProductsAction ()
    {
        $productIds = $this->getRequest()->getParam('product');

        if (! is_array($productIds)) {
            $this->_getSession()->addError(
            $this->__('Please select product(s)'));
        } else {
            try {
                foreach ($productIds as $productId) {
                    $product = Mage::getModel('catalog/product')->load(
                    $productId);

                    if ($this->massactionEventDispatchEnabled) {
                        Mage::dispatchEvent('catalog_product_prepare_save',
                            array('product' => $product,
                        	'request' => $this->getRequest())
                        );
                    }

                    $product->save();
                }

                $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) were successfully refreshed.',
                count($productIds)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function changeAttributeSetAction ()
    {
        $productIds = $this->getRequest()->getParam('product');
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        if (! is_array($productIds)) {
            $this->_getSession()->addError(
            $this->__('Please select product(s)'));
        } else {
            try {
                foreach ($productIds as $productId) {
                    $product = Mage::getSingleton('catalog/product')->unsetData()
                        ->setStoreId($storeId)
                        ->load($productId)
                        ->setAttributeSetId(
                            $this->getRequest()
                            ->getParam('attribute_set'))
                        ->setIsMassupdate(true)
                        ->save();
                }

                Mage::dispatchEvent('catalog_product_massupdate_after',
                array('products' => $productIds));
                $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) were successfully updated',
                count($productIds)));

            } catch (Exception $e) {
                $this->_getSession()->addException($e, $e->getMessage());
            }
        }

        $this->_redirect('adminhtml/catalog_product/index/', array());
    }
}