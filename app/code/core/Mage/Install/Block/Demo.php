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
 * @package     Mage_Install
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Install localization block
 *
 * @category   Mage
 * @package    Mage_Install
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Install_Block_Demo extends Mage_Install_Block_Abstract
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('install/demo.phtml');
    }

    /**
     * Retrieve locale data post url
     *
     * @return string
     */
    public function getPostUrl()
    {
        return $this->getUrl('*/*/demoPost');
    }

    public function getDemoAjaxUrl()
    {
        return $this->getUrl('*/*/demoAjax');
    }

    public function getDemoLogUrl()
    {
        return dirname($this->getBaseUrl()) . '/media/install';
    }

    public function getNextUrl()
    {
        return Mage::getSingleton('install/wizard')->getStepByName('demo')->getNextUrl();
    }
	
	public function getDemoDataCheck()
	{
		$itemClass = new Varien_Data_Form_Element_Checkbox(array('html_id' => 'demo', 'name' => 'config[demo]', 'checked' => true));
		$itemClass->setForm(new Varien_Data_Form());

		return $itemClass->toHtml();
	}

    public function getFormData()
    {
        $data = $this->getData('form_data');
        if (is_null($data)) {
            $data = new Varien_Object();
            $this->setData('form_data', $data);
        }
        return $data;
    }

}
