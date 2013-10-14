<?PHP

class Makingware_EnhancedProductManager_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getImageUrl ($image_file)
    {
        $url = false;
        $url = Mage::getBaseUrl('media') . 'catalog/product' . $image_file;
        
        return $url;
    }
    
    public function getFileExists ($image_file)
    {
        $file_exists = false;
        $file_exists = file_exists('media/catalog/product' . $image_file);
        
        return $file_exists;
    }
    
    public function getSearchCollection ($queryString, $request)
    {
        $request->setParam('q', $queryString);
        $searchquery = Mage::helper('catalogSearch')->getQuery();
        $searchquery->setStoreId(Mage::app()->getStore()->getId());
        $searchquery->save();
        
        return ($searchquery->getResultCollection());
    }
}