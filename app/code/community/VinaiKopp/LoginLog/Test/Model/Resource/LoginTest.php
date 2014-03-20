<?php


class VinaiKopp_LoginLog_Test_Model_Resource_LoginTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_Resource_Login';

    /**
     * @return VinaiKopp_LoginLog_Model_Resource_Login
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
    public function itShouldExtendCoreModelResourceDbAbstract()
    {
        $this->assertInstanceOf('Mage_Core_Model_Resource_Db_Abstract', $this->getInstance());
    }

    /**
     * @test
     */
    public function itsTableShouldBeInitialized()
    {
        $instance = $this->getInstance();
        $this->assertEquals('vinaikopp_login_log', $instance->getMainTable());
        $this->assertEquals('id', $instance->getIdFieldName());
    }
} 