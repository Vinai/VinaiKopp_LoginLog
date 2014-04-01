<?php


class VinaiKopp_LoginLog_Test_Model_System_Config_IpMaskTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_System_Config_IpMask';

    /**
     * @return VinaiKopp_LoginLog_Model_System_Config_IpMask
     */
    public function getInstance()
    {
        $mockHelper = $this->getMock('VinaiKopp_LoginLog_Helper_Data');
        $mockHelper->expects($this->any())
            ->method('__')
            ->will($this->returnArgument(0));
        return new $this->class($mockHelper);
    }
    
    /**
     * @test
     */
    public function itShouldExist()
    {
        $this->assertTrue(class_exists($this->class));
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodgetHelper()
    {
        $this->assertTrue(method_exists($this->class, 'getHelper'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodgetHelper
     */
    public function itShouldReturnTheInjectedHelper()
    {
        $instance = $this->getInstance();
        $helper = $instance->getHelper();
        $this->assertInstanceOf('PHPUnit_Framework_MockObject_MockObject', $helper);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodToOptionArray()
    {
        $this->assertTrue(method_exists($this->class, 'toOptionArray'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodToOptionArray
     */
    public function itShouldReturnAnOptionArray()
    {
        $instance = $this->getInstance();
        $options = $instance->toOptionArray();
        $this->assertTrue(is_array($options));
        foreach ($options as $option) {
            if (! is_array($option)) {
                $this->fail("OptionArray option is not an array");
            }
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
        }
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodToOptionHash()
    {
        $this->assertTrue(method_exists($this->class, 'toOptionHash'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodToOptionHash
     */
    public function itShouldReturnAnOptionHash()
    {
        $instance = $this->getInstance();
        $options = $instance->toOptionHash();
        $this->assertTrue(is_array($options));
        foreach ($options as $option) {
            $this->assertTrue(is_string($option));
        }
    }
} 