<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this Module to
 * newer versions in the future.
 *
 * @category   Magento
 * @package    VinaiKopp_LoginLog
 * @copyright  Copyright (c) 2014 Vinai Kopp http://netzarbeiter.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class VinaiKopp_LoginLog_Test_Config_AdminhtmlTest
    extends PHPUnit_Framework_TestCase
{
    protected function getFile()
    {
        $dir = Mage::getModuleDir('etc', 'VinaiKopp_LoginLog');
        return "$dir/adminhtml.xml";
    }
    
    protected function getXml()
    {
        $file = $this->getFile();
        $xml = simplexml_load_file($file);
        return $xml;
    }
    
    public function assertMenuDefined($path, $action = '', $message = '') {
        $nodePath = 'menu/' . implode('/children/', explode('/', $path));
        $result = $this->getXml()->xpath($nodePath);
        if (! $message) {
            $defaultMessage = sprintf('Failed to assert the menu %s is defined', $path);
        }
        if (! $result) {
            $this->fail($message ?: $defaultMessage);
        }
        if ($action && $action != (string) $result[0]->action) {
            if (! $message) {
                $defaultMessage = sprintf('Failed to assert the %s menu action %s is defined', $path, $action);
            }
            $this->fail($message ?: $defaultMessage);
        }
    }
    
    public function assertAclDefined($path, $message = null)
    {
        $nodePath = 'acl/resources/admin/children/' . implode('/children/', explode('/', $path));
        $result = $this->getXml()->xpath($nodePath);
        if (! $message) {
            $message = sprintf('Failed to assert the ACL %s is defined', $path);
        }
        if (! $result) {
            $this->fail($message);
        }
    }
    
    /**
     * @test
     */
    public function itShouldHaveAnAdminhtmlXml()
    {
        $this->assertFileExists($this->getFile());
    }

    /**
     * @test
     */
    public function itShouldAddAMenuEntry()
    {
        $this->assertMenuDefined('customer/login_log', 'adminhtml/loginlog');
    }

    /**
     * @test
     */
    public function itShouldAddAMenuAclEntry()
    {
        $this->assertAclDefined('customer/login_log');
    }

    /**
     * @test
     */
    public function itShouldAddASystemConfigAclEntry()
    {
        $this->assertAclDefined('system/config/vinaikopp_loginlog');
    }
    
    
} 