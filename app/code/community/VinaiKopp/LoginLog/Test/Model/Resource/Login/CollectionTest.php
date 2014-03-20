<?php


class VinaiKopp_LoginLog_Test_Model_Resource_Login_CollectionTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_Resource_Login_Collection';

    /**
     * @return VinaiKopp_LoginLog_Model_Resource_Login_Collection
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
    public function itShouldExtendCoreModelResourceDbCollectionAbstract()
    {
        $this->assertInstanceOf('Mage_Core_Model_Resource_Db_Collection_Abstract', $this->getInstance());
    }

    /**
     * @test
     */
    public function itsResourceModelShouldBeInitialized()
    {
        $instance = $this->getInstance();
        $this->assertEquals('vinaikopp_loginlog/login', $instance->getModelName());
        $this->assertEquals('vinaikopp_loginlog/login', $instance->getResourceModelName());
    }
} 