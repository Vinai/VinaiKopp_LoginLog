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

class VinaiKopp_LoginLog_Model_IpInfoDb
{
    const XML_CONF_API_KEY = 'vinaikopp_loginlog/lookup_api/ipinfodb_api_key';

    /**
     * @var Mage_Core_Model_Store
     */
    protected $_store;

    /**
     * See http://www.ipinfodb.com/ip_location_api.php for more api details
     *
     * @var string
     */
    protected $_apiUrl = 'http://api.ipinfodb.com/v3/ip-city/';

    /**
     * @var string
     */
    protected $_format = 'xml';

    /**
     * @param Mage_Core_Model_Store $store
     * @param string $apiUrl
     * @param string $format
     */
    public function __construct($store = null, $apiUrl = null, $format = null)
    {
        if ($store) {
            $this->_store = $store;
        }
        if ($apiUrl) {
            $this->_apiUrl = $apiUrl;
        }
        if ($format) {
            $this->_format = $format;
        }
    }

    /**
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        if (!$this->_store) {
            // @codeCoverageIgnoreStart
            $this->_store = Mage::app()->getStore();
        }
        // @codeCoverageIgnoreEnd
        return $this->_store;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getStore()->getConfig(self::XML_CONF_API_KEY);
    }

    /**
     * Build lookup query url
     *
     * http://api.ipinfodb.com/v3/ip-city/?key=<API-KEY>&ip=74.125.77.147&format=xml
     *
     * @param string $ipAddress
     * @return string
     */
    public function getApiUrlForIp($ipAddress)
    {
        $params = array(
            'key' => $this->getApiKey(),
            'ip' => (string)$ipAddress,
            'format' => $this->_format,
        );
        $url = '';
        foreach ($params as $key => $value) {
            $url .= $url ? '&' : '';
            $url .= urlencode($key) . '=' . urlencode($value);
        }
        $url = $this->_apiUrl . '?' . $url;
        return $url;
    }

    /**
     * @param string $ipAddress
     * @return string
     */
    public function getRawApiResponse($ipAddress)
    {
        $url = $this->getApiUrlForIp($ipAddress);
        $result = file_get_contents($url);
        return $result;
    }

    /**
     * @param $ipAddress
     * @return SimpleXMLElement
     * @throws Mage_Core_Exception
     */
    public function lookupIp($ipAddress)
    {
        $response = $this->getRawApiResponse($ipAddress);
        $xml = simplexml_load_string($response);
        if (!$xml || !isset($xml->statusCode)) {
            Mage::throwException('IpInfoDB response could not be parsed');
            // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd
        if ($xml->statusCode != 'OK') {
            Mage::throwException("{$xml->statusMessage}" ? : 'A unspecified error occurred');
            // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd
        return $xml;
    }
} 