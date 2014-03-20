<?php


class VinaiKopp_LoginLog_Test_Model_LoginTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_Login';

    /**
     * @return VinaiKopp_LoginLog_Model_Login
     */
    public function getInstance()
    {
        $instance = new $this->class();
        
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
} 