<?php
class Makingware_Cms_Block_Page_Tree extends Mage_Core_Block_Template
{
	protected $_page = null;
	
	public function setLimit($limit)
	{
		return $this->setData('limit', (int)$limit);
	}
	
	public function getLimit()
	{
		$limit = (int)$this->_getData('limit');
		return $limit > 0 ? $limit : null;
	}
	
	public function setKey($identifier)
	{
		return $this->setData('url_key', $identifier);
	}
	
	public function getKey()
	{
		return $this->getData('url_key', null);
	}
	
	public function setOrder($order)
	{
		return $this->setData('order', $order);
	}
	
	public function setSort($sort)
	{
		return $this->setData('sort', $sort);
	}
	
	public function getPage()
    {
        if (!$this->hasData('page')) {
        	$page = Mage::getSingleton('cms/page');
            if ($this->getPageId()) {
                $page->setStoreId(Mage::app()->getStore()->getId())
                    ->load($this->getPageId());
            }elseif ($this->getIdentifier()) {
                $page->setStoreId(Mage::app()->getStore()->getId())
                    ->load($this->getIdentifier(), 'identifier');
            }
            $this->setData('page', $page);
        }
        return $this->getData('page');
    }
    
    protected function getParentId($parentId = null)
    {
    	if (is_null($parentId)) {
    		if ($id = $this->getPage()->getId()) {
    			$parentId = $id;
    		}
    	}
    	empty($parentId) && $parentId = 0;
    	
    	static $data = array();	
    	if (!isset($data[$parentId])) {
    		$data[$parentId] = $parentId;
	    	if (is_string($parentId)) {
	    		$page = Mage::getModel('cms/page')->setStoreId(Mage::app()->getStore()->getId())->load($parentId, 'identifier');
	    		if ($page->getId()) {
	    			$data[$parentId] = $page->getId();
	    		}
	    	}
	    	is_numeric($data[$parentId]) or $data[$parentId] = 0;
    	}
    	return $data[$parentId];
    }
	
	public function getChildren($parentId = null, $limit = null, $kwargs = array())
    {
    	is_null($parentId) && $parentId = $this->getKey();
    	is_null($limit) && $limit = $this->getLimit();
    	$kwargs['order'] = $this->getData('order', null);
    	$kwargs['sort']	= $this->getData('sort', null);
    	
    	$parentId = $this->getParentId($parentId);
    	$pageSize = empty($limit) ? false : (int)$limit;
    	$order = empty($kwargs['order']) ? 'position' : (in_array($kwargs['order'], array('creation_time', 'update_time')) ? $kwargs['order'] : 'position');
    	$sort = empty($kwargs['sort']) ? 'ASC' : (preg_match('/asc|desc/i', $kwargs['sort']) ? strtoupper($kwargs['sort']) : 'ASC');
    	
    	$key = (string)$parentId . (string)$pageSize . $order . $sort;
    	
    	static $children = array();
    	if (empty($children[$key])) {
	        $children[$key] = $this->getPage()->getCollection()
	        	->addFieldToFilter('is_active', '1')
	            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
	            ->addFieldToFilter('parent_id', $parentId)
	            ->setOrder($order, $sort)
	        	->setPageSize($pageSize);
    	}
        return $children[$key];
    }

    public function getChildrenByCreateAt($parentId = null, $limit = null)
    {
    	return $this->getChildren($parentId, $limit, array('order' => 'creation_time', 'sort' => 'DESC'));
    }

    public function getChildrenByUpdateAt($parentId = null, $limit = null)
    {
    	return $this->getChildren($parentId, $limit, array('order' => 'update_time', 'sort' => 'DESC'));
    }
}
