<?php


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
            'setCustomerId', 'setEmail', 'setIp', 'setUserAgent', 'setLoginAt', 'save'
        ));
        
        $mockHttpHelper = $this->getHelperMock('core/http');
        
        $instance = new $this->class($mockLoginLog, $mockHttpHelper);

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
} 