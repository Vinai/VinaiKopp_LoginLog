<?php


class VinaiKopp_LoginLog_Test_Block_Adminhtml_LoginLog_LookupTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_Lookup';

    public function setUp()
    {
        parent::setUp();
        $helper = new VinaiKopp_LoginLog_Test_TestHelper();
        $helper->prepareAdminRequest();
    }

    /**
     * @param mixed $cached
     * @return VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_Lookup
     */
    public function getInstance($cached = false)
    {
        $mockLogin = $this->getModelMock('vinaikopp_loginlog/login');
        $mockLogin->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $mockLogin->expects($this->any())
            ->method('getIp')
            ->will($this->returnValue('127.0.0.1'));
        
        $mockCache = $this->getModelMock('core/cache');
        $mockCache->expects($this->any())
            ->method('load')
            ->will($this->returnValue($cached));
        
        $mockLookup = $this->getModelMock('vinaikopp_loginlog/ipInfoDb');
        return new $this->class(array(), $mockLogin, $mockCache, $mockLookup);
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
    public function itShouldHaveAMethodGetLoginLog()
    {
        $this->assertTrue(method_exists($this->class, 'getLoginLog'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetLoginLog
     */
    public function itShouldReturnTheInjectedLoginLog()
    {
        $instance = $this->getInstance();
        $result = $instance->getLoginLog();
        $this->assertInstanceOf('PHPUnit_Framework_MockObject_MockObject', $result);
        $this->assertInstanceOf('VinaiKopp_LoginLog_Model_Login', $result);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetCache()
    {
        $this->assertTrue(method_exists($this->class, 'getCache'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetCache
     */
    public function itShouldReturnTheInjectedCacheModel()
    {
        $instance = $this->getInstance();
        $result = $instance->getCache();
        $this->assertInstanceOf('PHPUnit_Framework_MockObject_MockObject', $result);
        $this->assertInstanceOf('Mage_Core_Model_Cache', $result);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodIsValidIp()
    {
        $this->assertTrue(method_exists($this->class, 'isValidIp'));
    }

    /**
     * @param string $ip
     * @param bool $expected
     * 
     * @test
     * @depends itShouldHaveAMethodIsValidIp
     * @dataProvider itShouldReturnTrueForValidIpAddressesProvider
     */
    public function itShouldReturnTrueForValidIpAddresses($ip, $expected)
    {
        $instance = $this->getInstance();
        $result = $instance->isValidIp($ip);
        $this->assertSame($expected, $result, "Result for $ip did not match expectation");
    }
    
    public function itShouldReturnTrueForValidIpAddressesProvider()
    {
        return array(
            array('127.0.0.1', true),
            array('127.0.0', false),
            array('127.0.0.', false),
            array('127.0.0.256', false),
            array('127.0.0.255', true),
            array('127.0.0000.1', false),
            array('127.0.000.1', true),
            array('127.0.0.xxx', false),
            array('127.0.0.1x', false),
            array('1.0.0.0', false),
            array('1.0.0.1', true),
        );
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetLookupModel()
    {
        $this->assertTrue(method_exists($this->class, 'getLookupModel'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetLookupModel
     */
    public function itShouldReturnTheInjectedIpInfoDbModel()
    {
        $instance = $this->getInstance();
        $result = $instance->getLookupModel();
        $this->assertInstanceOf('PHPUnit_Framework_MockObject_MockObject', $result);
        $this->assertInstanceOf('VinaiKopp_LoginLog_Model_LookupInterface', $result);
        $this->assertInstanceOf('VinaiKopp_LoginLog_Model_IpInfoDb', $result);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetLookupData()
    {
        $this->assertTrue(method_exists($this->class, 'getLookupData'));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetLookupData
     */
    public function itShouldCallLookupIpOnTheLookupModelIfNotCached()
    {
        $mockData = array('mock' => 'data');
        $cacheData = false;
        $instance = $this->getInstance($cacheData);
        $ip = $instance->getLoginLog()->getIp();
        /** @var PHPUnit_Framework_MockObject_MockObject $lookup */
        $lookup = $instance->getLookupModel();
        $lookup->expects($this->any())
            ->method('isLookupAvailable')
            ->will($this->returnValue(true));
        $lookup->expects($this->once())
            ->method('lookupIp')
            ->with($ip)
            ->will($this->returnValue($mockData));
        
        $instance->getLookupData();
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetLookupData
     */
    public function itShouldNotCallLookupIpOnTheLookupModelIfCached()
    {
        $mockData = array('mock' => 'data');
        $cacheData = serialize($mockData);
        $instance = $this->getInstance($cacheData);
        /** @var PHPUnit_Framework_MockObject_MockObject $lookup */
        $lookup = $instance->getLookupModel();
        $lookup->expects($this->any())
            ->method('isLookupAvailable')
            ->will($this->returnValue(true));
        $lookup->expects($this->never())
            ->method('lookupIp');
        
        $instance->getLookupData();
    }
} 