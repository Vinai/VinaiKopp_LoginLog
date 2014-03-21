<?php


class VinaiKopp_LoginLog_Test_Block_Adminhtml_LoginLog_List_GridTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List_Grid';

    /**
     * @return VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List_Grid
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
} 