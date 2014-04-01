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
class VinaiKopp_LoginLog_Test_Model_ObserverTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_Observer';

    /**
     * @return VinaiKopp_LoginLog_Model_Observer
     */
    public function getInstance()
    {
        $mockLoginLog = $this->getModelMock('vinaikopp_loginlog/login', array(
            'setCustomerId', 'setEmail', 'setIp', 'setUserAgent', 'setLoginAt', 'save', 'setLoggedOutAt'
        ));

        $mockHttpHelper = $this->getHelperMock('core/http');
        $sessionMock    = $this->mockSession('customer/session', array('getVinaiKoppLoginLogId'));

        $instance = new $this->class($mockLoginLog, $mockHttpHelper, $sessionMock);

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
    public function itShouldHaveAMethodCustomerLogin()
    {
        $this->assertTrue(is_callable(array($this->class, 'customerLogin')));
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodcustomerLogout()
    {
        $this->assertTrue(is_callable(array($this->class, 'customerLogout')));
    }

    /**
     * @test
     */
    public function itShouldLogCustomerLogins()
    {
        $model = $this->getInstance();
        /** @var EcomDev_PHPUnit_Mock_Proxy $mockLoginLog */
        $mockLoginLog = $model->getLoginLog();
        $mockLoginLog->expects($this->once())
            ->method('setCustomerId')
            ->with(1)
            ->will($this->returnSelf());
        $mockLoginLog->expects($this->once())
            ->method('setEmail')
            ->with('test@example.com')
            ->will($this->returnSelf());
        $mockLoginLog->expects($this->once())
            ->method('setIp')
            ->with('127.0.0.1')
            ->will($this->returnSelf());
        $mockLoginLog->expects($this->once())
            ->method('setUserAgent')
            ->with('TestDummyAgent')
            ->will($this->returnSelf());
        $mockLoginLog->expects($this->once())
            ->method('save')
            ->with()
            ->will($this->returnSelf());

        /** @var EcomDev_PHPUnit_Mock_Proxy $mockHttpHelper */
        $mockHttpHelper = $model->getCoreHttpHelper();
        $mockHttpHelper->expects($this->once())
            ->method('getRemoteAddr')
            ->with()
            ->will($this->returnValue('127.0.0.1'));
        $mockHttpHelper->expects($this->once())
            ->method('getHttpUserAgent')
            ->with()
            ->will($this->returnValue('TestDummyAgent'));

        $mockCustomer = $this->getModelMock('customer/customer', array('getId', 'getEmail'));
        $mockCustomer->expects($this->once())
            ->method('getId')
            ->with()
            ->will($this->returnValue(1));
        $mockCustomer->expects($this->once())
            ->method('getEmail')
            ->with()
            ->will($this->returnValue('test@example.com'));

        $mockEvent = $this->getMock('Varien_Event_Observer', array('getCustomer'));
        $mockEvent->expects($this->once())
            ->method('getCustomer')
            ->with()
            ->will($this->returnValue($mockCustomer));

        $model->customerLogin($mockEvent);
    }

    /**
     * @test
     */
    public function itShouldLogCustomerLogouts()
    {
        $model = $this->getInstance();
        /** @var EcomDev_PHPUnit_Mock_Proxy $mockLoginLog */
        $mockLoginLog = $model->getLoginLog();
        $mockLoginLog->expects($this->once())
            ->method('setLoggedOutAt')
            ->with(Varien_Date::now())
            ->will($this->returnSelf());
        $mockLoginLog->expects($this->once())
            ->method('load')
            ->with(815)
            ->will($this->returnSelf());
        $mockLoginLog->expects($this->once())
            ->method('save')
            ->with()
            ->will($this->returnSelf());

        $mockSession = $model->getSession();
        $mockSession->expects($this->once())
            ->method('getVinaiKoppLoginLogId')
            ->with()
            ->will($this->returnValue(815));

        $mockCustomer = $this->getModelMock('customer/customer', array('getId', 'getEmail'));
        $mockCustomer->expects($this->once())
            ->method('getId')
            ->with()
            ->will($this->returnValue(1));

        $mockCustomer->expects($this->once())
            ->method('getEmail')
            ->with()
            ->will($this->returnValue('test@example.com'));

        $mockEvent = $this->getMock('Varien_Event_Observer', array('getCustomer'));
        $mockEvent->expects($this->once())
            ->method('getCustomer')
            ->with()
            ->will($this->returnValue($mockCustomer));

        $model->customerLogout($mockEvent);
    }
}