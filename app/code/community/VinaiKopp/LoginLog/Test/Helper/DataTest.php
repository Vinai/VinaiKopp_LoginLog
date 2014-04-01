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
class VinaiKopp_LoginLog_Test_Helper_DataTest extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Helper_Data';

    public function getStoreMock($maskIpSetting = null)
    {
        $stubStore = $this->getMock('Mage_Core_Model_Store');
        $stubStore->expects($this->any())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/settings/mask_ip_address')
            ->will($this->returnValue($maskIpSetting));
        return $stubStore;
    }
    
    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockStore
     * @return VinaiKopp_LoginLog_Helper_Data
     */
    public function getInstance($mockStore = null)
    {
        if (! $mockStore) {
            $mockStore = $this->getStoreMock();
        }
        return new $this->class($mockStore);
    }

    /**
     * @test
     */
    public function itShouldExist()
    {
        $this->assertInstanceOf($this->class, $this->getInstance());
    }

    /**
     * @test
     */
    public function itShouldExtendTheHelperAbstract()
    {
        $this->assertInstanceOf('Mage_Core_Helper_Abstract', $this->getInstance());
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodMaskIpAddress()
    {
        $this->assertTrue(is_callable(array($this->class, 'maskIpAddress')));
    }

    /**
     * @test
     * @dataProvider ipMaskDataProvider
     */
    public function itShouldMaskTheIpAddress($ip, $maskIpSetting, $expected)
    {
        $mockStore = $this->getStoreMock($maskIpSetting);
        $instance = $this->getInstance($mockStore);
        $result   = $instance->maskIpAddress($ip);
        $this->assertEquals($expected, $result);
    }
    
    public function ipMaskDataProvider()
    {
        return array(
            array('192.168.0.1', 0, '192.168.0.1'),
            array('192.168.0.1', 1, '192.168.0.xxx'),
            array('192.168.0.1', 2, '192.168.xxx.xxx'),
            array('192.168.0.1', 3, '192.xxx.xxx.xxx'),
            array('192.168.0.1', 4, 'xxx.xxx.xxx.xxx'),
        );
    }

    /**
     * @test
     */
    public function itShouldReturnTheInputForInvalidIpAddresses()
    {
        $mockStore = $this->getStoreMock(1);
        $instance = $this->getInstance($mockStore);
        $result   = $instance->maskIpAddress('1234');
        $this->assertEquals('1234', $result);
    }

    /**
     * @test
     */
    public function itShouldHaveAGetStoreMethod()
    {
        $this->assertTrue(method_exists($this->class, 'getStore'));
    }

    /**
     * @test
     * @depends itShouldHaveAGetStoreMethod
     */
    public function itShouldReturnTheInjectedStoreModel()
    {
        $mockStore = $this->getMock('Mage_Core_Model_Store');
        $instance = $this->getInstance($mockStore);
        $this->assertSame($mockStore, $instance->getStore());
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetMaskIpSetting()
    {
        $this->assertTrue(method_exists($this->class, 'getMaskIpSetting'));
    }

    /**
     * @test
     */
    public function itShouldReturnTheMaskIpConfigSetting()
    {
        $mockStore = $this->getStoreMock(1);
        $instance = $this->getInstance($mockStore);
        $this->assertSame(1, $instance->getMaskIpSetting());
    }
}