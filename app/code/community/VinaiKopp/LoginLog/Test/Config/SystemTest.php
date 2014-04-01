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


class VinaiKopp_LoginLog_Test_Config_SystemTest
    extends PHPUnit_Framework_TestCase
{
    protected function getFile()
    {
        $dir = Mage::getModuleDir('etc', 'VinaiKopp_LoginLog');
        return "$dir/system.xml";
    }

    protected function getXml()
    {
        $file = $this->getFile();
        $xml  = simplexml_load_file($file);
        return $xml;
    }

    public function assertFieldDefined($path, $message = '')
    {
        @list($section, $group, $field) = explode('/', $path);
        $nodePath = "sections/$section/groups/$group/fields/$field";
        $result   = $this->getXml()->xpath($nodePath);
        if (!$message) {
            $defaultMessage = sprintf("System configuration field \"$path\" not defined");
        }
        if (!$result) {
            $this->fail($message ? : $defaultMessage);
        }
    }

    /**
     * @test
     */
    public function itShouldHaveASystemXml()
    {
        $this->assertFileExists($this->getFile());
    }

    /**
     * @test
     */
    public function itShouldHaveAnApiUrlField()
    {
        $this->assertFieldDefined('vinaikopp_loginlog/lookup_api/ipinfodb_api_key');
    }

    /**
     * @test
     */
    public function itShouldHaveAMaskIpField()
    {
        $this->assertFieldDefined('vinaikopp_loginlog/privacy/mask_ip_address');
    }
}