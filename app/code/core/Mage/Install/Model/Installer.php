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
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Installer model
 *
 * @category   Mage
 * @package    Mage_Install
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Install_Model_Installer extends Varien_Object
{

    /**
     * Installer host response used to check urls
     *
     */
    const INSTALLER_HOST_RESPONSE   = 'MAGENTO';

    /**
     * Installer data model used to store data between installation steps
     *
     * @var Varien_Object
     */
    protected $_dataModel;

    /**
     * Checking install status of application
     *
     * @return bool
     */
    public function isApplicationInstalled()
    {
        return Mage::isInstalled();
    }

    /**
     * Get data model
     *
     * @return Varien_Object
     */
    public function getDataModel()
    {
        if (is_null($this->_dataModel)) {
            $this->setDataModel(Mage::getSingleton('install/session'));
        }
        return $this->_dataModel;
    }

    /**
     * Set data model to store data between installation steps
     *
     * @param Varien_Object $model
     * @return Mage_Install_Model_Installer
     */
    public function setDataModel(Varien_Object $model)
    {
        $this->_dataModel = $model;
        return $this;
    }

    /**
     * Check packages (pear) downloads
     *
     * @return boolean
     */
    public function checkDownloads()
    {
        try {
            $result = Mage::getModel('install/installer_pear')->checkDownloads();
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }
        $this->setDownloadCheckStatus($result);
        return $result;
    }

    /**
     * Check server settings
     *
     * @return bool
     */
    public function checkServer()
    {
        try {
            Mage::getModel('install/installer_filesystem')->install();

            Mage::getModel('install/installer_env')->install();
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }
        $this->setData('server_check_status', $result);
        return $result;
    }

    /**
     * Retrieve server checking result status
     *
     * @return unknown
     */
    public function getServerCheckStatus()
    {
        $status = $this->getData('server_check_status');
        if (is_null($status)) {
            $status = $this->checkServer();
        }
        return $status;
    }

    /**
     * Installation config data
     *
     * @param   array $data
     * @return  Mage_Install_Model_Installer
     */
    public function installConfig($data)
    {
        $data['db_active'] = true;
        Mage::getSingleton('install/installer_db')->checkDatabase($data);
        Mage::getSingleton('install/installer_config')
            ->setConfigData($data)
            ->install();
        return $this;
    }

    /**
     * Database installation
     *
     * @return Mage_Install_Model_Installer
     */
    public function installDb()
    {
        Mage_Core_Model_Resource_Setup::applyAllUpdates();
        $data = $this->getDataModel()->getConfigData();

        /**
         * Saving host information into DB
         */
        $setupModel = new Mage_Core_Model_Resource_Setup('core_setup');

        if (!empty($data['use_rewrites'])) {
            $setupModel->setConfigData(Mage_Core_Model_Store::XML_PATH_USE_REWRITES, 1);
        }

        if (!empty($data['enable_charts'])) {
            $setupModel->setConfigData(Mage_Adminhtml_Block_Dashboard::XML_PATH_ENABLE_CHARTS, 1);
        } else {
            $setupModel->setConfigData(Mage_Adminhtml_Block_Dashboard::XML_PATH_ENABLE_CHARTS, 0);
        }


        $unsecureBaseUrl = Mage::getBaseUrl('web');
        if (!empty($data['unsecure_base_url'])) {
            $unsecureBaseUrl = $data['unsecure_base_url'];
            $setupModel->setConfigData(Mage_Core_Model_Store::XML_PATH_UNSECURE_BASE_URL, $unsecureBaseUrl);
        }

        if (!empty($data['use_secure'])) {
            $setupModel->setConfigData(Mage_Core_Model_Store::XML_PATH_SECURE_IN_FRONTEND, 1);
            $setupModel->setConfigData(Mage_Core_Model_Store::XML_PATH_SECURE_BASE_URL, $data['secure_base_url']);
            if (!empty($data['use_secure_admin'])) {
                $setupModel->setConfigData(Mage_Core_Model_Store::XML_PATH_SECURE_IN_ADMINHTML, 1);
            }
        }
        elseif (!empty($data['unsecure_base_url'])) {
            $setupModel->setConfigData(Mage_Core_Model_Store::XML_PATH_SECURE_BASE_URL, $unsecureBaseUrl);
        }

        /**
         * Saving locale information into DB
         */
        $locale = $this->getDataModel()->getLocaleData();
        if (!empty($locale['locale'])) {
            $setupModel->setConfigData(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $locale['locale']);
        }
        if (!empty($locale['timezone'])) {
            $setupModel->setConfigData(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE, $locale['timezone']);
        }
        if (!empty($locale['currency'])) {
            $setupModel->setConfigData(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE, $locale['currency']);
            $setupModel->setConfigData(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_DEFAULT, $locale['currency']);
            $setupModel->setConfigData(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_ALLOW, $locale['currency']);
        }

          /**
         * 汉化1,8
         */
		$fireeavtable=Mage::getSingleton('core/resource')->getTableName('eav_attribute');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$eavEntityTypetable=Mage::getSingleton('core/resource')->getTableName('eav_entity_type');
		$custommerEntityTypeId=$read->fetchOne("select entity_type_id from ".$eavEntityTypetable." where entity_type_code='customer'");
		$categoryEntityTypeId=$read->fetchOne("select entity_type_id from ".$eavEntityTypetable." where entity_type_code='catalog_category'");
		$productEntityTypeId=$read->fetchOne("select entity_type_id from ".$eavEntityTypetable." where entity_type_code='catalog_product'");

		$write->query("update ".$fireeavtable." set frontend_label='关联到网站' where frontend_label='Associate to Website' ");
		$write->query("update ".$fireeavtable." set frontend_label='创建于' where frontend_label='Create In' ");
		$write->query("update ".$fireeavtable." set frontend_label='创建于' where frontend_label='Created From' ");
		$write->query("update ".$fireeavtable." set frontend_label='前缀',frontend_input='hidden' where frontend_label='Prefix' ");
		$write->query("update ".$fireeavtable." set frontend_label='姓名' where frontend_label='Name' and entity_type_id='$custommerEntityTypeId'");
		$write->query("update ".$fireeavtable." set frontend_label='分类名' where frontend_label='Name' and entity_type_id='$categoryEntityTypeId'");
		$write->query("update ".$fireeavtable." set frontend_label='名称' where frontend_label='Name' and entity_type_id='$productEntityTypeId'");
		$write->query("update ".$fireeavtable." set frontend_label='后缀',frontend_input='hidden' where frontend_label='Suffix' ");
		$write->query("update ".$fireeavtable." set frontend_label='邮件' where frontend_label='Email' ");
		$write->query("update ".$fireeavtable." set frontend_label='组别' where frontend_label='Group' ");
		$write->query("update ".$fireeavtable." set frontend_label='生日' where frontend_label='Date Of Birth' ");
		$write->query("update ".$fireeavtable." set frontend_label='默认收货地址' where frontend_label='Default Shipping Address' ");
		$write->query("update ".$fireeavtable." set frontend_label='税/增值税号' where frontend_label='Tax/VAT Number' ");
		$write->query("update ".$fireeavtable." set frontend_label='被确认' where frontend_label='Is Confirmed' ");
		$write->query("update ".$fireeavtable." set frontend_label='创建于' where frontend_label='Created At' ");
		$write->query("update ".$fireeavtable." set frontend_label='公司' where frontend_label='Company' ");
		$write->query("update ".$fireeavtable." set frontend_label='街道地址' where frontend_label='Street Address' ");
		$write->query("update ".$fireeavtable." set frontend_label='地区' where frontend_label='Area' ");
		$write->query("update ".$fireeavtable." set frontend_label='城市' where frontend_label='City' ");
		$write->query("update ".$fireeavtable." set frontend_label='国家' where frontend_label='Country' ");
		$write->query("update ".$fireeavtable." set frontend_label='省/直辖市' where frontend_label='State/Province' ");
		$write->query("update ".$fireeavtable." set frontend_label='邮编' where frontend_label='Zip/Postal Code' ");
		$write->query("update ".$fireeavtable." set frontend_label='电话' where frontend_label='Phone' ");
		$write->query("update ".$fireeavtable." set frontend_label='电话' where frontend_label='Telephone' ");
		$write->query("update ".$fireeavtable." set frontend_label='传真' where frontend_label='Fax' ");
		$write->query("update ".$fireeavtable." set frontend_label='性别' where frontend_label='Gender' ");
		$write->query("update ".$fireeavtable." set frontend_label='商品名' where frontend_label='Name' ");
		$write->query("update ".$fireeavtable." set frontend_label='激活' where frontend_label='Is Active' ");
		$write->query("update ".$fireeavtable." set frontend_label='商品描述' where frontend_label='Description' ");
		$write->query("update ".$fireeavtable." set frontend_label='图片' where frontend_label='Image' ");
		$write->query("update ".$fireeavtable." set frontend_label='meta标题' where frontend_label='Page Title' ");
		$write->query("update ".$fireeavtable." set frontend_label='meta关键字' where frontend_label='Meta Keywords' ");
		$write->query("update ".$fireeavtable." set frontend_label='meta描述' where frontend_label='Meta Description' ");
		$write->query("update ".$fireeavtable." set frontend_label='显示模式' where frontend_label='Display Mode' ");
		$write->query("update ".$fireeavtable." set frontend_label='静态块' where frontend_label='CMS Block' ");
		$write->query("update ".$fireeavtable." set frontend_label='固定分类' where frontend_label='Is Anchor' ");
		$write->query("update ".$fireeavtable." set frontend_label='路径' where frontend_label='Path' ");
		$write->query("update ".$fireeavtable." set frontend_label='位置' where frontend_label='Position' ");
		$write->query("update ".$fireeavtable." set frontend_label='使用模板' where frontend_label='Custom Design' ");
		$write->query("update ".$fireeavtable." set frontend_label='适用于' where frontend_label='Apply To' ");
		$write->query("update ".$fireeavtable." set frontend_label='生效日期' where frontend_label='Active From' ");
		$write->query("update ".$fireeavtable." set frontend_label='失效日期' where frontend_label='Active To' ");
		$write->query("update ".$fireeavtable." set frontend_label='页面布局' where frontend_label='Page Layout' ");
		$write->query("update ".$fireeavtable." set frontend_label='自定义布局更新' where frontend_label='Custom Layout Update' ");
		$write->query("update ".$fireeavtable." set frontend_label='自定义XML布局' where frontend_label='Custom Layout Update' ");
		$write->query("update ".$fireeavtable." set frontend_label='等级' where frontend_label='Level' ");
		$write->query("update ".$fireeavtable." set frontend_label='子类数' where frontend_label='Children Count' ");
		$write->query("update ".$fireeavtable." set frontend_label='可用的产品列表排序' where frontend_label='Available Product Listing Sort by' ");
		$write->query("update ".$fireeavtable." set frontend_label='默认产品列表排序' where frontend_label='Default Product Listing Sort by' ");
		$write->query("update ".$fireeavtable." set frontend_label='简介' where frontend_label='Short Description' ");
		$write->query("update ".$fireeavtable." set frontend_label='价格' where frontend_label='Price' ");
		$write->query("update ".$fireeavtable." set frontend_label='特价' where frontend_label='Special Price' ");
		$write->query("update ".$fireeavtable." set frontend_label='特价起始时间' where frontend_label='Special Price From Date' ");
		$write->query("update ".$fireeavtable." set frontend_label='特价失效时间' where frontend_label='Special Price To Date' ");
		$write->query("update ".$fireeavtable." set frontend_label='成本' where frontend_label='Cost' ");
		$write->query("update ".$fireeavtable." set frontend_label='重量' where frontend_label='Weight' ");
		$write->query("update ".$fireeavtable." set frontend_label='品牌' where frontend_label='Manufacturer' ");
		$write->query("update ".$fireeavtable." set frontend_label='meta标题' where frontend_label='Meta Title' ");
		$write->query("update ".$fireeavtable." set frontend_label='基本图片' where frontend_label='Base Image' ");
		$write->query("update ".$fireeavtable." set frontend_label='小图' where frontend_label='Small Image' ");
		$write->query("update ".$fireeavtable." set frontend_label='缩略图' where frontend_label='Thumbnail' ");
		$write->query("update ".$fireeavtable." set frontend_label='双轨价格' where frontend_label='Tier Price' ");
		$write->query("update ".$fireeavtable." set frontend_label='图片' where frontend_label='Media Gallery' ");
		$write->query("update ".$fireeavtable." set frontend_label='会员价' where frontend_label='Tier Price' ");
		$write->query("update ".$fireeavtable." set frontend_label='颜色' where frontend_label='Color' ");
		$write->query("update ".$fireeavtable." set frontend_label='新品起始日期' where frontend_label='Set Product as New from Date' ");
		$write->query("update ".$fireeavtable." set frontend_label='新品结束日期' where frontend_label='Set Product as New to Date' ");
		$write->query("update ".$fireeavtable." set frontend_label='热卖起始日期' where frontend_label='Set Product as BestSellers from Date' ");
		$write->query("update ".$fireeavtable." set frontend_label='热卖结束日期' where frontend_label='Set Product as BestSellers to Date' ");
		$write->query("update ".$fireeavtable." set frontend_label='图片' where frontend_label='Image Gallery' ");
		$write->query("update ".$fireeavtable." set frontend_label='状态' where frontend_label='Status' ");
		$write->query("update ".$fireeavtable." set frontend_label='税务类' where frontend_label='Tax Class' ");
		$write->query("update ".$fireeavtable." set frontend_label='最低价格' where frontend_label='Minimal Price' ");
		$write->query("update ".$fireeavtable." set frontend_label='可见' where frontend_label='Visibility' ");
		$write->query("update ".$fireeavtable." set frontend_label='产品选项位置' where frontend_label='Display product options in' ");
		$write->query("update ".$fireeavtable." set frontend_label='图像标签' where frontend_label='Image Label' ");
		$write->query("update ".$fireeavtable." set frontend_label='小图标签' where frontend_label='Small Image Label' ");
		$write->query("update ".$fireeavtable." set frontend_label='缩略图标签' where frontend_label='Thumbnail Label' ");
		$write->query("update ".$fireeavtable." set frontend_label='可添加礼品赠言' where frontend_label='Allow Gift Message' ");
		$write->query("update ".$fireeavtable." set frontend_label='价格查看' where frontend_label='Price View' ");
		$write->query("update ".$fireeavtable." set frontend_label='配送方式' where frontend_label='Shipment' ");
		$write->query("update ".$fireeavtable." set frontend_label='链接可单独购买' where frontend_label='Links can be purchased separately' ");
		$write->query("update ".$fireeavtable." set frontend_label='样品名称' where frontend_label='Samples title' ");
		$write->query("update ".$fireeavtable." set frontend_label='链接标题' where frontend_label='Links title' ");
		$write->query("update ".$fireeavtable." set frontend_label='商品编号' where frontend_label='SKU' ");
		$write->query("update ".$fireeavtable." set frontend_label='用户名' where frontend_label='Username'");
		$write->query("update ".$fireeavtable." set frontend_label='手机' where frontend_label='Mobile'");
		$write->query("update ".$fireeavtable." set frontend_label='缩略图' where frontend_label='Thumbnail Image' ");
		$write->query("update ".$fireeavtable." set frontend_label='在导航菜单显示' where frontend_label='Include in Navigation Menu' ");
		$write->query("update ".$fireeavtable." set frontend_label='礼物卡额配置（默认为空）' where frontend_label='GC Amount Configuration (leave empty for default configuration)' ");
		$fireeavtableset=Mage::getSingleton('core/resource')->getTableName('eav_attribute_set');
		$write->query("update ".$fireeavtableset." set attribute_set_name='默认' where attribute_set_name='Default' ");
        return $this;
    }

    /**
     * Prepare admin user data in model and validate it.
     * Returns TRUE or array of error messages.
     *
     * @param array $data
     * @return mixed
     */
    public function validateAndPrepareAdministrator($data)
    {
        $user = Mage::getModel('admin/user')
            ->load($data['username'], 'username');
        $user->addData($data);

        $result = $user->validate();
        if (is_array($result)) {
            foreach ($result as $error) {
                $this->getDataModel()->addError($error);
            }
            return $result;
        }
        return $user;
    }

    /**
     * Create admin user.
     * Paramater can be prepared user model or array of data.
     * Returns TRUE or throws exception.
     *
     * @param mixed $data
     * @return bool
     */
    public function createAdministrator($data)
    {
        $user = Mage::getModel('admin/user')
            ->load('admin', 'username');
        if ($user && $user->getPassword()=='4297f44b13955235245b2497399d7a93') {
            $user->delete();
        }

        //to support old logic checking if real data was passed
        if (is_array($data)) {
            $data = $this->validateAndPrepareAdministrator($data);
            if (is_array(data)) {
                throw new Exception(Mage::helper('install')->__('Please correct the user data and try again.'));
            }
        }

        //run time flag to force saving entered password
        $data->setForceNewPassword(true);

        $data->save();
        $data->setRoleIds(array(1))->saveRelations();

        return true;
    }

    /**
     * Validating encryption key.
     * Returns TRUE or array of error messages.
     *
     * @param $key
     * @return unknown_type
     */
    public function validateEncryptionKey($key)
    {
        $errors = array();

        try {
            if ($key) {
                Mage::helper('core')->validateKey($key);
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
            $this->getDataModel()->addError($e->getMessage());
        }

        if (!empty($errors)) {
            return $errors;
        }

        return true;
    }

    /**
     * Set encryption key
     *
     * @param string $key
     * @return Mage_Install_Model_Installer
     */
    public function installEnryptionKey($key)
    {
        if ($key) {
            Mage::helper('core')->validateKey($key);
        }
        Mage::getSingleton('install/installer_config')->replaceTmpEncryptKey($key);
        return $this;
    }

    public function finish()
    {
        Mage::getSingleton('install/installer_config')->replaceTmpInstallDate();
        Mage::app()->cleanCache();

        $cacheData = array();
        foreach (Mage::helper('core')->getCacheTypes() as $type=>$label) {
            $cacheData[$type] = 1;
        }
        Mage::app()->saveUseCache($cacheData);
        return $this;
    }

}
