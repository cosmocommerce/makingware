<?php
class Makingware_Cms_Model_Mysql4_Page extends Mage_Cms_Model_Mysql4_Page
{
    protected function _construct()
    {
        parent::_construct();
        Mage::getSingleton('core/resource')->setMappedTableName('cms_page', Mage::getConfig()->getTablePrefix() . 'cms_page_tree');
    }

    public function changeParent(Mage_Cms_Model_Page $page, Mage_Cms_Model_Page $newParent, $afterPageId = null)
    {
        $childrenCount  = $this->getChildrenCount($page->getId()) + 1;
        $table          = $this->getTable('cms/page');
        $adapter        = $this->_getWriteAdapter();
        $pageId         = $page->getId();
        /**
         * Decrease children count for all old page parent pages
         */
        $this->decreaseChildrenCount($page, $page->getParentIds());
        /**
         * Increase children count for new page parents
         */
        $this->increaseChildrenCount($page, $newParent->getPathIds());

        $position = $this->_processPositions($page, $newParent, $afterPageId);

        $newPath = $newParent->getPath().'/'.$page->getId();
        #$identifiers = explode('/', $page->getIdentifier());
        #$newIdentifier = trim($newParent->getIdentifier().'/'.array_pop($identifiers), '/');
        $newIdentifier = $page->getIdentifier();
        $newLevel= $newParent->getLevel()+1;
        $levelDisposition = $newLevel - $page->getLevel();

        /**
         * Update children nodes identifiers
         */
        #$this->updateChildrenIdentifiers($page, $newIdentifier);

        /**
         * Update children nodes path
         */
        $sql = "UPDATE {$table} SET
            `path`  = REPLACE(`path`, '{$page->getPath()}/', '{$newPath}/'),
            `level` = `level` + {$levelDisposition}
            WHERE ". $adapter->quoteInto('path LIKE ?', $page->getPath().'/%');
        $adapter->query($sql);

        /**
         * Update moved page data
         */
        $data = array(
        	'path'       => $newPath,
        	'level'      => $newLevel,
        	'identifier' => $newIdentifier,
            'position'   => $position,
            'parent_id'  => $newParent->getId()
        );
        $adapter->update($table, $data, $adapter->quoteInto('page_id=?', $page->getId()));

        // Update page object to new data
        $page->addData($data);

        return $this;
    }

    public function decreaseChildrenCount(Mage_Cms_Model_Page $page, $pageIds)
    {
        $this->_updateChildrenCount($page, $pageIds, '-');
    }

    public function increaseChildrenCount(Mage_Cms_Model_Page $page, $pageIds)
    {
        $this->_updateChildrenCount($page, $pageIds, '+');
    }

    protected function _updateChildrenCount(Mage_Cms_Model_Page $page, $pageIds, $operator)
    {
        $table          = $this->getTable('cms/page');
        $childrenCount  = $this->getChildrenCount($page->getId()) + 1;
        $adapter        = $this->_getWriteAdapter();
        $sql = "UPDATE {$table} SET children_count=children_count {$operator} {$childrenCount} WHERE page_id IN(?)";
        $adapter->query($adapter->quoteInto($sql, $pageIds));
    }

    public function updateChildrenIdentifiers(Mage_Cms_Model_Page $page, $identifier)
    {
        $table          = $this->getTable('cms/page');
        $adapter        = $this->_getWriteAdapter();
        $sql = "UPDATE {$table} SET
            `identifier`  = REPLACE(`identifier`, '{$page->getIdentifier()}/', '{$identifier}/')
            WHERE ". $adapter->quoteInto('path LIKE ?', $page->getPath().'/%');
        $adapter->query($sql);

        return $this;
    }

    public function getChildrenCount($pageId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('cms/page'), 'children_count')
            ->where('page_id=?', $pageId);

        $child = $this->_getReadAdapter()->fetchOne($select);

        return $child;
    }

    public function getStoreRootId($storeId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('cms/page'), 'page_id')
            ->where('parent_id=0')
            ->where('store_id=?', $storeId);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function checkIdentifier($identifier, $storeId)
    {
        $path = $this->getPath($identifier, $storeId);
        $select = $this->_getReadAdapter()->select()->from(array('main_table'=>$this->getMainTable()), 'page_id')
            ->where('main_table.path=?', $path)
            ->where('main_table.is_active=1 AND main_table.store_id = ?', $storeId)
            ->order('main_table.store_id DESC');

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function lookupStoreIds($id)
    {
        return $this->_getReadAdapter()->fetchCol($this->_getReadAdapter()->select()
            ->from($this->getTable('cms/page'), 'store_id')
            ->where("{$this->getIdFieldName()} = ?", $id)
        );
    }

    public function getPath($identifier, $storeId)
    {
        $map = $this->getIdentifierPageIdMap($identifier, $storeId);
        $path = $identifier;

        foreach ($map as $key => $pageId) {
            $path = str_replace($key, $pageId, $path);
        }

        $select = $this->_getReadAdapter()->select()->from(array('main_table'=>$this->getMainTable()), array('page_id'))
            ->where('main_table.parent_id=0 AND main_table.level=1')
            ->where('main_table.is_active=1 AND main_table.store_id = ?', $storeId);
        $rootId = $this->_getReadAdapter()->fetchOne($select);

        $path = $rootId.'/'.$path;

        return $path;
    }

    public function getIdentifierPath($path, $storeId)
    {
        $map = $this->getPageIdIdentifierMap($path, $storeId);
        $path = substr($path, (strpos($path, '/') + 1));
        $path = explode('/', $path);

        foreach ($path as $key => $value) {
            if (isset($map[$value])) {
                $path[$key] = $map[$value];
            }
        }

        return implode('/', $path);
    }

    public function getIdentifierPageIdMap($identifier, $storeId)
    {
        $identifiers = explode('/', $identifier);
        $select = $this->_getReadAdapter()->select()->from(array('main_table'=>$this->getMainTable()), array('identifier', 'page_id'))
            ->where('main_table.identifier IN (?)', $identifiers)
            ->where('main_table.is_active=1 AND main_table.store_id = ?', $storeId);

        return $this->_getReadAdapter()->fetchPairs($select);
    }

    public function getPageIdIdentifierMap($path, $storeId)
    {
        $pageIds = explode('/', $path);
        $select = $this->_getReadAdapter()->select()->from(array('main_table'=>$this->getMainTable()), array('page_id', 'identifier'))
            ->where('main_table.page_id IN (?)', $pageIds)
            ->where('main_table.is_active=1 AND main_table.store_id = ?', $storeId);

        return $this->_getReadAdapter()->fetchPairs($select);
    }

    public function getCmsPageTitleByIdentifier($identifier)
    {
        $select = $this->_getReadAdapter()->select();
        /* @var $select Zend_Db_Select */
        $select->from(array('main_table' => $this->getMainTable()), 'title')
            ->where('main_table.identifier = ?', $identifier)
            ->where('main_table.store_id = ?', $this->getStore()->getId())
            ->order('main_table.store_id DESC');
        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getIsUniquePageToStores(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getWriteAdapter()->select()
                ->from($this->getMainTable())
                ->where($this->getMainTable().'.identifier = ?', $object->getData('identifier'))
                ->where($this->getMainTable().'.store_id = ?', $object->getStoreId());
        if ($object->getId()) {
            $select->where($this->getMainTable().'.page_id <> ?',$object->getId());
        }

        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    protected function isValidPageIdentifier(Mage_Core_Model_Abstract $object)
    {
        // Homepage case
        if (!$object->getParentId() && $object->getData('identifier') === '') {
            return true;
        }
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        return $this;
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        return $this;
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable())
            ->where($this->getMainTable().'.'.$field.'=?', $value);
        return $select;
    }

    protected function _processPositions($page, $newParent, $afterPageId)
    {
        $table          = $this->getTable('cms/page');
        $adapter        = $this->_getWriteAdapter();
        
        $position = $key = 0;
        
        if ($afterPageId) {
	        $positiones = array();
	        $stmt = $adapter->query("SELECT `page_id` FROM {$table} WHERE parent_id = {$newParent->getId()} ORDER BY `position` ASC");
	        while ($pageId = $stmt->fetchColumn()) {
	        	$positiones[$key] = $pageId;
	        	$key += 2;
	        }
	        
	        $afterKey = array_search($afterPageId, $positiones);
	        if (is_numeric($afterKey)) {
		        if (!in_array($page->getId(), $positiones)) {
		        	$positiones[$afterKey+1] = $page->getId();
		        }else {
		        	$selfKey = array_search($page->getId(), $positiones);
		        	if (is_numeric($selfKey)) {
		        		$positiones[$afterKey+1] = $positiones[$selfKey];
		        		unset($positiones[$selfKey]);
		        	}
		        }
	        }
	        ksort($positiones);
	        
	        $key = 0;
	        foreach ($positiones as $pageId) {
	        	if ($pageId == $page->getId()) {
	        		$position = $key;
	        	}else {
	        		$adapter->query("UPDATE {$table} SET `position` = {$key} WHERE `page_id` = {$pageId}");
	        	}
	        	$key++;
	        }
        }else {
        	$sql = "SELECT MIN(`position`) FROM {$table} WHERE parent_id=?";
        	$position = $adapter->fetchOne($adapter->quoteInto($sql, $newParent->getId())) + 1;
        }
        
        /*$sql = "UPDATE {$table} SET `position`=`position`-1 WHERE "
            . $adapter->quoteInto('parent_id=? AND ', $page->getParentId())
            . $adapter->quoteInto('position>?', $page->getPosition());
        $adapter->query($sql);
        
        if ($afterPageId) {
            $sql = "SELECT `position` FROM {$table} WHERE page_id=?";
            $position = $adapter->fetchOne($adapter->quoteInto($sql, $afterPageId));

            $sql = "UPDATE {$table} SET `position`=`position`+1 WHERE "
                . $adapter->quoteInto('parent_id=? AND ', $newParent->getId())
                . $adapter->quoteInto('position>?', $position);
            $adapter->query($sql);
        } elseif ($afterPageId !== null) {
            $position = 0;
            $sql = "UPDATE {$table} SET `position`=`position`+1 WHERE "
                . $adapter->quoteInto('parent_id=? AND ', $newParent->getId())
                . $adapter->quoteInto('position>?', $position);
            $adapter->query($sql);
        } else {
            $sql = "SELECT MIN(`position`) FROM {$table} WHERE parent_id=?";
            $position = $adapter->fetchOne($adapter->quoteInto($sql, $newParent->getId()));
        }
        $position+=1;*/

        return $position;
    }
}
