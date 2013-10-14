<?php

class Makingware_EnhancedProductManager_Block_Widget_Grid_Column_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    protected function _transformActionData (&$action, &$actionCaption, Varien_Object $row)
    {
        foreach ($action as $attibute => $value) {
            if (isset($action[$attibute]) && ! is_array($action[$attibute])) {
                $this->getColumn()->setFormat($action[$attibute]);
                $action[$attibute] = parent::render($row);
            } else {
                $this->getColumn()->setFormat(null);
            }
            
            switch ($attibute) {
                case 'caption':
                    $actionCaption = $action['caption'];
                    unset($action['caption']);
                    break;
                case 'url':
                    if (is_array($action['url'])) {
                        $params = array(
                        $action['field'] => $this->_getValue($row));
                        
                        if (isset($action['url']['params'])) {
                            $params = array_merge($action['url']['params'], 
                            $params);
                        }
                        
                        $action['href'] = $this->getUrl($action['url']['base'], 
                        $params);
                        unset($action['field']);
                    } else {
                        $action['href'] = $action['url'];
                    }
                    
                    unset($action['url']);
                    break;
                case 'popup':
                    $action['onclick'] = 'popWin(this.href, \'windth=800,height=700,resizable=1,scrollbars=1\');return false;';
                    break;
            }
        }
        
        return $this;
    }
}