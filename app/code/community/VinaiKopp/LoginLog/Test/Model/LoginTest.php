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
        Mage::unregister('_resource_singleton/vinaikopp_loginlog/login');
        
        $mockHelper = $this->getMock('VinaiKopp_LoginLog_Helper_Data');
        $mockHelper->expects($this->any())
            ->method('maskIpAddress')
            ->will($this->returnArgument(0));
        $mockSession = $this->getMockBuilder('Mage_Customer_Model_Session')
            ->disableOriginalConstructor()
            ->getMock();


        $mockHttpHelper = $this->getHelperMock('core/http');
        
        $instance = new $this->class($now, $mockHelper, $mockSession, $mockHttpHelper);
        
        return $instance;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCustomer()
    {
        $mockCustomer = $this->getMock(
            'Mage_Customer_Model_Customer', array('getId', 'getEmail')
        );
        $mockCustomer->expects($this->any())
            ->method('getEmail')
            ->with()
            ->will($this->returnValue('test@example.com'));
        $mockCustomer->expects($this->any())
            ->method('getId')
            ->with()
            ->will($this->returnValue(1));
        
        return $mockCustomer;        
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
    public function itShouldHaveAMethodRegisterLogin()
    {
        $this->assertTrue(method_exists($this->class, 'registerLogin'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodRegisterLogin
     */
    public function itShouldRegisterALogin()
    {
        $now = '2014-01-01 12:12:12';
        $instance = $this->getInstance($now);
        $mockHttpHelper = $instance->getCoreHttpHelper();
        $mockHttpHelper->expects($this->any())
            ->method('getRemoteAddr')
            ->with()
            ->will($this->returnValue('127.0.0.1'));
        $mockHttpHelper->expects($this->any())
            ->method('getHttpUserAgent')
            ->with()
            ->will($this->returnValue('TestDummyAgent'));
        
        $mockCustomer = $this->getMockCustomer();
        
        $instance->registerLogin($mockCustomer);
        
        $this->assertEquals($now, $instance->getLoginAt());
        $this->assertEquals($mockCustomer->getId(), $instance->getCustomerId());
        $this->assertEquals('test@example.com', $instance->getEmail());
        $this->assertEquals('127.0.0.1', $instance->getIp());
        $this->assertEquals('TestDummyAgent', $instance->getUserAgent());
        $this->assertNull($instance->getLogoutAt());
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
    public function itShouldSetTheLogoutDateWhenRegisterLogoutIsCalled()
    {
        $now = '2014-01-01 12:12:12';
        $mockLoginLogEntityId = 1;
        $instance = $this->getInstance($now);
        $mockCustomer = $this->getMockCustomer();
        
        $session = $instance->getSession();
        $session->expects($this->atLeastOnce())
            ->method('getData')
            ->with('vinaikopp_loginlog_id')
            ->will($this->returnValue($mockLoginLogEntityId));
        
        $mockResource = Mage::getResourceModel('vinaikopp_loginlog/login');
        $mockResource->expects($this->once())
            ->method('load')
            ->with($instance, $mockLoginLogEntityId, null)
            ->will($this->returnCallback(function($login) use ($mockCustomer) {
                $login->setCustomerId($mockCustomer->getId());
            }));
        
        $this->assertNull($instance->getLogoutAt());
        $instance->registerLogout($mockCustomer);
        $this->assertEquals($now, $instance->getLogoutAt());
    }

    /**
     * Making sure object is refactored correctly
     * 
     * @test
     */
    public function itShouldNotSetTheLoginDateBeforeSave()
    {
        $now = '2014-01-01 12:12:12';
        $instance = $this->getInstance($now);
        $this->assertNull($instance->getLoginAt());
        $instance->setSomethingChanged(true);
        $instance->save();
        $this->assertNull($instance->getLoginAt());
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
        $this->assertInstanceOf('VinaiKopp_LoginLog_Helper_Data', $instance->getHelper());
    }
    

    /**
     * @test
     */
    public function itShouldHaveAMethodGetCoreHttpHelper()
    {
        $this->assertTrue(method_exists($this->class, 'getCoreHttpHelper'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetCoreHttpHelper
     */
    public function itShouldReturnTheInjectedCoreHttpHelper()
    {
        $instance = $this->getInstance();
        $this->assertInstanceOf('PHPUnit_Framework_MockObject_MockObject', $instance->getCoreHttpHelper());
        $this->assertInstanceOf('Mage_Core_Helper_Http', $instance->getCoreHttpHelper());
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
        $this->assertInstanceOf('Mage_Customer_Model_Session', $instance->getSession());
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
} 