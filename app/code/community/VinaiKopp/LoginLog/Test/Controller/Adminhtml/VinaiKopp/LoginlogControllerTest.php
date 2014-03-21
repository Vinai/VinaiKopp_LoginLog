<?php


class VinaiKopp_LoginLog_Test_Controller_Adminhtml_VinaiKopp_LoginlogControllerTest
    extends EcomDev_PHPUnit_Test_Case_Controller
{
    protected $class = 'VinaiKopp_LoginLog_Adminhtml_LoginlogController';

    public function setUp()
    {
        parent::setUp();
        $dir = Mage::getModuleDir('controllers', 'VinaiKopp_LoginLog');
        $file = "$dir/Adminhtml/LoginlogController.php";
        $result = stream_resolve_include_path($file);
        if (false !== $result) {
            require_once $file;
        }
        
        $helper = new VinaiKopp_LoginLog_Test_TestHelper();
        $helper->prepareAdminRequest();
    }
    
    public function tearDown()
    {
        parent::tearDown();
        Mage::unregister('_singleton/index/indexer');
    }

    /**
     * @return VinaiKopp_LoginLog_Adminhtml_LoginLogController
     */
    public function getInstance()
    {

        $request = $this->app()->getRequest();
        $response = $this->app()->getResponse();
        return new $this->class($request, $response);
    }

    /**
     * @test
     */
    public function itShouldCheckTheAcl()
    {
        $ctrl = $this->getInstance();

        $mockSession = $this->getModelMock('admin/session');
        $mockSession->expects($this->once())
            ->method('isAllowed')
            ->with('customer/login_log')
            ->will($this->returnValue(true));
        Mage::unregister('_singleton/admin/session');
        Mage::register('_singleton/admin/session', $mockSession);

        $method = new ReflectionMethod($this->class, '_isAllowed');
        $method->setAccessible(true);
        $result = $method->invoke($ctrl, '_isAllowed');
        $this->assertTrue($result);
    }

    public function testIndexAction()
    {
        $this->dispatch('adminhtml/loginlog/index');
        $this->assertLayoutLoaded();
        $this->assertLayoutHandleLoaded('adminhtml_loginlog_index');
        $this->assertLayoutBlockTypeOf('vinaikopp.loginlog.list', 'vinaikopp_loginlog/adminhtml_loginLog_list');
        $this->assertLayoutBlockCreated('vinaikopp.loginlog.list');
        $this->assertLayoutBlockInstanceOf('vinaikopp.loginlog.list', 'VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List');
        
        // Block not 
        $child = $this->app()->getLayout()->getBlock('adminhtml_loginLog_list.grid');
        $this->assertInstanceOf('Mage_Adminhtml_Block_Widget_Grid', $child);
    }

    public function testGridAction()
    {
        $this->dispatch('adminhtml/loginlog/grid');
        $this->assertLayoutLoaded();
        $this->assertLayoutHandleLoaded('adminhtml_loginlog_grid');
        $this->assertLayoutHandleNotLoaded('default');
        $this->assertLayoutBlockCreated('vinaikopp.loginlog.grid');
        $this->assertLayoutBlockInstanceOf('vinaikopp.loginlog.grid', 'VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List_Grid');
    }
} 