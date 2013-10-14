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
 * Install data helper
 */
class Mage_Install_Helper_Data extends Mage_Core_Helper_Abstract
{
	/*
	 *	move directory
	 */
	 public function moveDirectory($source, $target)
	 {
		if (! is_dir($source)) {
			Mage::throwException(
				Mage::helper('install')->__('This is not a valid directory') . ': ' . $source
			);
		}
		if (! is_dir($target)) {@mkdir($target);}
	 
		if ($handle = opendir($source)) {
			while (false !== ($file = readdir($handle))) {
				if ($file == '.' || $file == '..' || $file == '.svn') {continue ;}
				$sourceFile = $source . DS . $file;
				$targetFile = $target . DS . $file;

				if (is_dir($sourceFile)) {
					$this->moveDirectory($sourceFile, $targetFile);
					#@rmdir($sourceFile);
					continue ;
				}
				copy($sourceFile, $targetFile);
				#@unlink($sourceFile);
			}
		}
	 }

     public function setProgress(Array $data, $file = null)
     {
         empty($file) && $file = Mage::getBaseDir('media') . DS . 'install';
         $content = $this->getProgress(null, $file);
         is_array($content) or $content = array($content);
         $data = array_merge($content, $data);

         file_put_contents($file, json_encode($data));
         return $this;
     }

     public function getProgress($name = null, $file = null, $defaultValue = '')
     {
         empty($file) && $file = Mage::getBaseDir('media') . DS . 'install';
         if (! is_file($file)) {
             return $defaultValue;
         }

         $data = json_decode(file_get_contents($file), true);

         if (empty ($name)) {
             return $data;
         }
         return isset($data[$name]) ? $data[$name] : $defaultValue;
     }
     
     public function sendInstallationInformation($action = '')
     {
         $information = array(
       		 'ACTION'		   => $action,
       		 'SESSION_ID'	   => session_id(),
             'CN_VERSION'      => Mage::getCNVersion(),
             'PHP_VERSION'	   => PHP_VERSION,
             'SERVER_SOFTWARE' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : (isset($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : ''),
             'HTTP_HOST'	   => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : ''),
             'REMOTE_ADDR'	   => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : ''),
             'REQUEST_TIME'    => isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time(),
             'HTTP_USER_AGENT' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
         );
		 
         $connection = Mage::getModel('core/resource_setup', 'core_setup')->getConnection();

		 if ($connection) {
			 $information['MYSQL_VERSION'] = $connection->query('SELECT VERSION();')->fetchColumn();
		 };
         
         $uri = 'http://ce.makingware.com/installNotify/';
         $client = new Varien_Http_Client($uri);
         return $client->setParameterPost($information)->request(Varien_Http_Client::POST);
     }

}
