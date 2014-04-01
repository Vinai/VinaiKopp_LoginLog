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


class VinaiKopp_LoginLog_Test_Model_LoginTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_Login';

    /**
     * @param string $now
     * @return VinaiKopp_LoginLog_Model_Login
     */
    public function getInstance($now = null)
    {
        $mockResource = $this->getResourceModelMock('vinaikopp_loginlog/login');
        $mockResource->expects($this->any())
            ->method('addCommitCallback')
            ->will($this->returnSelf());
        $this->app()->getConfig()->replaceInstanceCreation(
            'resource_model', 'vinaikopp_loginlog/login', $mockResource
        );
        
        $mockHelper = $this->getMock('VinaiKopp_LoginLog_Helper_Data');
        $mockSession = $this->getMockBuilder('Mage_Customer_Model_Session')
            ->disableOriginalConstructor()
            ->getMock();
        $instance = new $this->class($now, $mockHelper, $mockSession);
        
        return $instance;
    }

    /**
     * @test
     */
    public function itShouldExist()
    {
        $this->assertTrue(class_exists($this->class), "Failed asserting {$this->class} exists");
    }

    /**
     * @test
     */
    public function itShouldExtendCoreModelAbstract()
    {
        $this->assertInstanceOf('Mage_Core_Model_Abstract', $this->getInstance());
    }

    /**
     * @test
     */
    public function itsResourceModelShouldBeInitialized()
    {
        $instance = $this->getInstance();
        $this->assertEquals('vinaikopp_loginlog/login', $instance->getResourceName());
    }

    /**
     * @test
     */
    public function itShouldSetTheLoginDateBeforeSave()
    {
        $now = '2014-01-01 12:12:12';
        $instance = $this->getInstance($now);
        $this->assertNull($instance->getLoginAt());
        $instance->setSomethingChanged(true);
        $instance->save();
        $this->assertEquals($now, $instance->getLoginAt());
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetHelper()
    {
        $this->assertTrue(method_exists($this->class, 'getHelper'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetHelper
     */
    public function itShouldReturnTheInjectedHelper()
    {
        $instance = $this->getInstance();
        $this->assertInstanceOf('PHPUnit_Framework_MockObject_MockObject', $instance->getHelper());
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetSession()
    {
        $this->assertTrue(method_exists($this->class, 'getSession'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetSession
     */
    public function itShouldReturnTheInjectedSession()
    {
        $instance = $this->getInstance();
        $this->assertInstanceOf('PHPUnit_Framework_MockObject_MockObject', $instance->getSession());
    }

    /**
     * @test
     */
    public function itShouldSetTheLoginLogIdInTheCustomerSession()
    {
        $instance = $this->getInstance();
        /** @var PHPUnit_Framework_MockObject_MockObject $session */
        $session = $instance->getSession();
        $session->expects($this->once())
            ->method('setData')
            ->with('vinaikopp_loginlog_id', 1)
            ->will($this->returnSelf());
        $instance->setId(1);
        $instance->afterCommitCallback();
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodRegisterLogout()
    {
        $this->assertTrue(method_exists($this->class, 'registerLogout'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodRegisterLogout
     */
    public function itShouldSetTheLogoutDate()
    {
        $now = '2014-01-01 12:12:12';
        $instance = $this->getInstance($now);
        $this->assertNull($instance->getLogoutAt());
        $instance->registerLogout();
        $this->assertEquals($now, $instance->getLogoutAt());
    }
} 