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