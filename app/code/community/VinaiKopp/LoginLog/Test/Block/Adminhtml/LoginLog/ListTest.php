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


class VinaiKopp_LoginLog_Test_Block_Adminhtml_LoginLog_ListTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List';

    public function setUp()
    {
        parent::setUp();
        $helper = new VinaiKopp_LoginLog_Test_TestHelper();
        $helper->prepareAdminRequest();
    }
    
    /**
     * @return VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List
     */
    public function getInstance()
    {
        return new $this->class;
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
    public function itShouldSpecifyABlockGroupProperty()
    {
        $block = $this->getInstance();
        $this->assertAttributeEquals('vinaikopp_loginlog', '_blockGroup', $block);
    }

    /**
     * @test
     */
    public function itShouldSpecifyAControllerProperty()
    {
        $block = $this->getInstance();
        $this->assertAttributeEquals('adminhtml_loginLog_list', '_controller', $block);
    }

    /**
     * @test
     */
    public function itShouldSpecifyAHeaderText()
    {
        $block = $this->getInstance();
        $block->setLayout($this->app()->getLayout());
        
        $this->assertEquals('Customer Logins', $block->getHeaderText());
    }

    /**
     * @test
     */
    public function itShouldNotHaveAnAddButton()
    {
        $block = $this->getInstance();
        $block->setLayout($this->app()->getLayout());
        
        $buttons = new ReflectionProperty($this->class, '_buttons');
        $buttons->setAccessible(true);
        $found = false;
        foreach ($buttons->getValue($block) as $button) {
            if (is_array($button) && key($button) == 'add') {
                $found = true;
                break;
            }
        }
        if ($found) {
            $this->fail("Block contains an add button");
        }
    }
}