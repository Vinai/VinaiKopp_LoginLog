<?php


class VinaiKopp_LoginLog_Test_Config_ConfigTest
    extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * @test
     */
    public function itShouldHaveASetupResource()
    {
        $this->assertModuleVersionGreaterThanOrEquals('0.1.0');
        $this->assertSetupResourceDefined();
        $this->assertSetupResourceExists();
    }

    /**
     * @test
     */
    public function itShouldHaveATableDeclaration()
    {
        $this->assertTableAlias('vinaikopp_loginlog/login_log', 'vinaikopp_login_log');
    }

    /**
     * @test
     */
    public function itShouldHaveAHelper()
    {
        $this->assertHelperAlias('vinaikopp_loginlog', 'VinaiKopp_LoginLog_Helper_Data');
    }

    /**
     * @test
     */
    public function itShouldHaveACustomerLoginEntity()
    {
        $this->assertModelAlias('vinaikopp_loginlog/login', 'VinaiKopp_LoginLog_Model_Login');
        $this->assertResourceModelAlias(
            'vinaikopp_loginlog/login', 'VinaiKopp_LoginLog_Model_Resource_Login'
        );
        $this->assertResourceModelAlias(
            'vinaikopp_loginlog/login_collection', 'VinaiKopp_LoginLog_Model_Resource_Login_Collection'
        );
    }

    /**
     * @test
     */
    public function itShouldHaveACustomerLoginObserver()
    {
        $this->assertEventObserverDefined('frontend', 'customer_login', 'vinaikopp_loginlog/observer', 'customerLogin');
    }

    /**
     * @test
     */
    public function itShouldHaveAnAdminhtmlLayout()
    {
        $this->assertLayoutFileDefined('adminhtml', 'vinaikopp/loginlog.xml');
        $this->assertLayoutFileExists('adminhtml', 'vinaikopp/loginlog.xml');
        $this->assertLayoutFileExistsInTheme('adminhtml', 'vinaikopp/loginlog.xml', 'default', 'default');
    }

    /**
     * @test
     */
    public function itShouldHaveABlockAlias()
    {
        $this->assertBlockAlias('vinaikopp_loginlog/login_log', 'VinaiKopp_LoginLog_Block_Login_Log');
    }

    /**
     * @test
     */
    public function itShouldHaveAnAdminRoute()
    {
        $this->assertRouteModule('adminhtml', 'VinaiKopp_LoginLog_Adminhtml', 'admin');
    }
} 