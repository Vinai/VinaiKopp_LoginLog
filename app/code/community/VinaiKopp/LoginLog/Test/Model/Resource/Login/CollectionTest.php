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


class VinaiKopp_LoginLog_Test_Model_Resource_Login_CollectionTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_Resource_Login_Collection';

    /**
     * @return VinaiKopp_LoginLog_Model_Resource_Login_Collection
     */
    public function getInstance()
    {
        $instance = new $this->class;

        return $instance;
    }

    /**
     * @param string $columnName
     * @param Varien_Db_Select $select
     * @param string $message
     */
    public function assertNotColumnPresent(
        $columnName, Varien_Db_Select $select, $message = ''
    )
    {
        $this->assertColumnPresent($columnName, $select, $message, false);
    }

    /**
     * @param string $columnName
     * @param Varien_Db_Select $select
     * @param string $message
     * @param bool $assertPresent Negate the assertion to not present
     */
    public function assertColumnPresent(
        $columnName, Varien_Db_Select $select, $message = '', $assertPresent = true
    )
    {
        $found = false;
        $columns = $select->getPart('columns');
        foreach ($columns as $column) {
            if (isset($column[2]) && $column[2] === $columnName) {
                $found = true;
                break;
            }
        }
        if ($found !== $assertPresent) {
            if (! $message) {
                if ($assertPresent) {
                    $message = 'Column "%s" not present on select';
                } else {
                    $message = 'Column "%s" present on select';
                }
                $message = sprintf($message, $columnName);
            }
            $this->fail($message);
        }
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

    /**
     * @test
     */
    public function itShouldHaveAMethodAddDuration()
    {
        $this->assertTrue(method_exists($this->class, 'addDuration'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodAddDuration
     */
    public function itShouldAddADurationValue()
    {
        $instance = $this->getInstance();
        $select = $instance->getSelect();
        
        $this->assertNotColumnPresent('duration', $select);
        $instance->addDuration();
        $this->assertColumnPresent('duration', $select);
    }
} 