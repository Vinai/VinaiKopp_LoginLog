<?php


class VinaiKopp_LoginLog_Test_Helper_DataTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Helper_Data';
    
    public function getInstance()
    {
        return new $this->class;
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
} 