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


class VinaiKopp_LoginLog_Test_Model_IpInfoDbTest extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'VinaiKopp_LoginLog_Model_IpInfoDb';
    
    protected $successResponseXml = <<<EOT
<Response>
   <statusCode>OK</statusCode>
   <statusMessage/>
   <ipAddress>74.125.77.147</ipAddress>
   <countryCode>US</countryCode>
   <countryName>UNITED STATES</countryName>
   <regionName>CALIFORNIA</regionName>
   <cityName>MOUNTAIN VIEW</cityName>
   <zipCode>94043</zipCode>
   <latitude>37.3956</latitude>
   <longitude>-122.076</longitude>
   <timeZone>-08:00</timeZone>
</Response>
EOT;
    
    protected $failResponseXml = <<<EOT
<Response>
   <statusCode>OK</statusCode>
   <statusMessage/>
   <ipAddress>xs</ipAddress>
   <countryCode/>
   <countryName/>
   <regionName/>
   <cityName/>
   <zipCode/>
   <latitude/>
   <longitude/>
   <timeZone/>
</Response>
EOT;


    /**
     * @param string $apiUrl
     * @param string $format
     * @return VinaiKopp_LoginLog_Model_IpInfoDb
     */
    public function getInstance($apiUrl = null, $format = null)
    {
        $stubStore = $this->getModelMock('core/store');
        
        /** @var VinaiKopp_LoginLog_Model_IpInfoDb $instance */
        $instance = new $this->class($stubStore, $apiUrl, $format);
        
        return $instance;
    }

    /**
     * @throws PHPUnit_Framework_SkippedTestError
     */
    protected function checkVfsStreamIsAvailable()
    {
        // Register vfs autoloader via EcomDev_PHPUnit fixture model
        $this->getFixture()->getVfs();

        if (! class_exists('\org\bovigo\vfs\vfsStream', false)) {
            $this->markTestSkipped('vfsStream not available');
        }
    }

    /**
     * @param string $stubKey
     * @param string $stubIp
     * @param string $stubFormat
     * @param string $response
     * @return string
     * @throws PHPUnit_Framework_SkippedTestError
     */
    protected function prepareApiMock($stubKey, $stubIp, $stubFormat, $response)
    {
        $this->checkVfsStreamIsAvailable();
        
        $stubBaseUrl = 'example.com/v3/ip-city/'; // omit the http schema for vfs
        $stubQuery = "?key=$stubKey&ip=$stubIp&format=$stubFormat";
        $vfsDirStructure = array('v3' => array('ip-city' => array()));

        $vfsApiRoot = \org\bovigo\vfs\vfsStream::setup(
            $stubBaseUrl, '555', $vfsDirStructure
        );
        $vfsApiDir = $vfsApiRoot->getChild('v3')->getChild('ip-city');
        \org\bovigo\vfs\vfsStream::newFile($stubQuery)
            ->withContent($response)
            ->at($vfsApiDir);

        // Prefix the vfs schema to the base URL
        $vfsBaseUrl = \org\bovigo\vfs\vfsStream::url($stubBaseUrl);
        return $vfsBaseUrl;
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertStringContains($needle, $haystack, $message = '')
    {
        $result = strpos($haystack, $needle);
        if (false === $result) {
            if (! $message) {
                $message = sprintf('Expected string not found');
            }
            $this->fail($message . "\n-$needle\n+$haystack");
        }
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
    public function itShouldImplementTheLookupInterface()
    {
        $instance = $this->getInstance();
        $this->assertInstanceOf('VinaiKopp_LoginLog_Model_LookupInterface', $instance);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetApiKey()
    {
        $this->assertTrue(is_callable(array($this->class, 'getApiKey')));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetApiKey
     */
    public function itShouldReturnTheApiKey()
    {
        $obj = $this->getInstance();

        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $obj->getStore();
        $store->expects($this->once())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue('dummy-key'));
        
        $this->assertEquals('dummy-key', $obj->getApiKey());
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetApiUrlForIp()
    {
        $this->assertTrue(is_callable(array($this->class, 'getApiUrlForIp')));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetApiUrlForIp
     */
    public function itShouldReturnTheApiBaseUrl()
    {
        $obj = $this->getInstance('https://test.com/');

        $result = $obj->getApiUrlForIp('');
        $this->assertStringContains('https://test.com/', $result);
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetApiUrlForIp
     */
    public function itShouldReturnTheApUrlWithFormat()
    {
        $obj = $this->getInstance(null, 'xml');

        $result = $obj->getApiUrlForIp('');
        $this->assertStringContains('format=xml', $result);
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetApiUrlForIp
     */
    public function itShouldReturnTheApiUrlWithIp()
    {
        $obj = $this->getInstance();
        
        $result = $obj->getApiUrlForIp('0.0.0.0');
        $this->assertStringContains('ip=0.0.0.0', $result);
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetApiUrlForIp
     */
    public function itShouldReturnTheApiUrlWithApiKey()
    {
        $obj = $this->getInstance();
        
        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $obj->getStore();
        $store->expects($this->once())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue('dummy-key'));
        
        $result = $obj->getApiUrlForIp('');
        $this->assertStringContains('key=dummy-key', $result);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodGetRawApiResponse()
    {
        $this->assertTrue(is_callable(array($this->class, 'getRawApiResponse')));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodGetRawApiResponse
     */
    public function itShouldQueryTheApiToGetTheResponse()
    {
        $key = 'dummy-key';
        $ip = '0.0.0.0';
        $format = 'xml';
        
        $vfsBaseUrl = $this->prepareApiMock(
            $key, $ip, $format, $this->successResponseXml
        );

        $obj = $this->getInstance($vfsBaseUrl, $format);

        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $obj->getStore();
        $store->expects($this->once())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue($key));
        
        $response = $obj->getRawApiResponse($ip);
        
        $this->assertEquals($this->successResponseXml, $response);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodLookupIp()
    {
        $this->assertTrue(is_callable(array($this->class, 'lookupIp')));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodLookupIp
     */
    public function itShouldReturnTheResponseAsAnArray()
    {
        $key = 'dummy-key';
        $ip = '0.0.0.0';
        $format = 'xml';

        $vfsBaseUrl = $this->prepareApiMock(
            $key, $ip, $format, $this->successResponseXml
        );

        $obj = $this->getInstance($vfsBaseUrl, $format);

        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $obj->getStore();
        $store->expects($this->once())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue($key));

        $response = $obj->lookupIp($ip);

        $expected = simplexml_load_string($this->successResponseXml);
        $this->assertEquals((array) $expected->children(), $response);
    }

    /**
     * @test
     * @depends itShouldHaveAMethodLookupIp
     * @expectedException Mage_Core_Exception
     * @expectedExceptionMessage A unspecified error occurred
     */
    public function itShouldThrowAnExceptionIfResponseNotOk()
    {
        $key = 'dummy-key';
        $ip = '0.0.0.0';
        $format = 'xml';
        $responseData = str_replace('>OK<', '>ERR<', $this->failResponseXml);

        $vfsBaseUrl = $this->prepareApiMock(
            $key, $ip, $format, $responseData
        );

        $obj = $this->getInstance($vfsBaseUrl, $format);

        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $obj->getStore();
        $store->expects($this->once())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue($key));

        $obj->lookupIp($ip);
    }

    /**
     * @test
     * @depends itShouldHaveAMethodLookupIp
     * @expectedException Mage_Core_Exception
     * @expectedExceptionMessage IpInfoDB response could not be parsed
     */
    public function itShouldThrowAnExceptionIfResponseEmpty()
    {
        $key = 'dummy-key';
        $ip = '0.0.0.0';
        $format = 'xml';
        $responseData = '';

        $vfsBaseUrl = $this->prepareApiMock(
            $key, $ip, $format, $responseData
        );

        $obj = $this->getInstance($vfsBaseUrl, $format);

        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $obj->getStore();
        $store->expects($this->once())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue($key));

        $obj->lookupIp($ip);
    }

    /**
     * @test
     */
    public function itShouldHaveAMethodIsLookupAvailable()
    {
        $this->assertTrue(is_callable(array($this->class, 'isLookupAvailable')));
    }

    /**
     * @test
     * @depends itShouldHaveAMethodIsLookupAvailable
     */
    public function itShouldReturnTrueIfAnApiKeyIsSet()
    {
        $instance = $this->getInstance();
        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $instance->getStore();
        $store->expects($this->any())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue('test123'));
        
        $this->assertTrue($instance->isLookupAvailable());
    }

    /**
     * @test
     * @depends itShouldHaveAMethodIsLookupAvailable
     */
    public function itShouldReturnFalseIfNoApiKeyIsSet()
    {
        $instance = $this->getInstance();
        /** @var PHPUnit_Framework_MockObject_MockObject $store */
        $store = $instance->getStore();
        $store->expects($this->any())
            ->method('getConfig')
            ->with('vinaikopp_loginlog/lookup_api/ipinfodb_api_key')
            ->will($this->returnValue(''));
        
        $this->assertFalse($instance->isLookupAvailable());
    }
}