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

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockStore
     * @param int $maskIpSetting
     * @return VinaiKopp_LoginLog_Helper_Data
     */
    public function getInstance($mockStore = null, $maskIpSetting = null)
    {
        if (! $mockStore) {
            $maskIpSetting = $maskIpSetting ? : 0;
            $mockStore = $this->getMock('Mage_Core_Model_Store');
            $mockStore->expects($this->any())
                ->method('getConfig')
                ->with('vinaikopp_loginlog/settings/mask_ip_address')
                ->will($this->returnValue($maskIpSetting));
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
     * @loadFixture
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldNotMaskIp($ip)
    {
        $result   = $this->getInstance()->maskIpAddress($ip);
        $expected = $this->expected($ip)->getData('0');
        $this->assertEquals($expected, $result);
    }

    /**
     * @loadFixture
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldMaskIpTwoBytes($ip)
    {
        $result   = $this->getInstance()->maskIpAddress($ip);
        $expected = $this->expected($ip)->getData('0');
        $this->assertEquals($expected, $result);
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
}