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