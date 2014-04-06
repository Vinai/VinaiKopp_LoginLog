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
        $setters = array('setCustomerId', 'setEmail', 'setIp', 'setUserAgent',
            'setLoginAt');
        $nonSetters = array('load', 'save', 'registerLogout', 'registerLogin', 'getCustomerId');
        $allMethods = array_merge($setters, $nonSetters);
        $mockLoginLog = $this->getModelMock('vinaikopp_loginlog/login', $allMethods);
        
        foreach ($setters as $method) {
            $mockLoginLog->expects($this->any())
                ->method($method)
                ->will($this->returnSelf());
        }

        $instance = new $this->class($mockLoginLog);

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
        $this->assertTrue(method_exists($this->class, 'customerLogin'));
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodcustomerLogout()
    {
        $this->assertTrue(method_exists($this->class, 'customerLogout'));
    }

    /**
     * @test
     */
    public function itShouldLogCustomerLogins()
    {
        $model = $this->getInstance();
        
        $mockCustomer = $this->getModelMock('customer/customer', array('getId', 'getEmail'));

        $mockEvent = $this->getMock('Varien_Event_Observer', array('getCustomer'));
        $mockEvent->expects($this->once())
            ->method('getCustomer')
            ->with()
            ->will($this->returnValue($mockCustomer));
        
        $mockLoginLog = $model->getLoginLog();
        $mockLoginLog->expects($this->once())
            ->method('registerLogin')
            ->with($mockCustomer)
            ->will($this->returnValue(true));
        $mockLoginLog->expects($this->once())
            ->method('save')
            ->with()
            ->will($this->returnSelf());

        $model->customerLogin($mockEvent);
    }

    /**
     * @test
     */
    public function itShouldLogCustomerLogouts()
    {
        $model = $this->getInstance();

        $mockCustomer = $this->getModelMock('customer/customer');

        $mockEvent = $this->getMock('Varien_Event_Observer', array('getCustomer'));
        $mockEvent->expects($this->once())
            ->method('getCustomer')
            ->will($this->returnValue($mockCustomer));

        $mockLoginLog = $model->getLoginLog();
        $mockLoginLog->expects($this->once())
            ->method('registerLogout')
            ->with($mockCustomer)
            ->will($this->returnValue(true));
        
        $mockLoginLog->expects($this->once())
            ->method('save');

        $model->customerLogout($mockEvent);
    }
}