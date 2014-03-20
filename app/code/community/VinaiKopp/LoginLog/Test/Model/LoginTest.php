<?php


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
        $instance = new $this->class($now);
        
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
} 