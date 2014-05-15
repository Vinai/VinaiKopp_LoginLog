<?php

class VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_Lookup
    extends Mage_Adminhtml_Block_Template
{
    const LOOKUP_CACHE_TAG = 'vinaikopp_loginlog_lookup';

    /**
     * Keys in the return array of getLookupData()
     *
     * @var array
     */
    protected $_lookupKeys = array(
        'statusCode',
        'statusMessage',
        'ipAddress',
        'countryCode',
        'countryName',
        'regionName',
        'cityName',
        'zipCode',
        'latitude',
        'longitude',
        'timezone',
    );

    /**
     * @var int
     */
    protected $_lookupCacheHours = 24;

    /**
     * @var VinaiKopp_LoginLog_Model_Login
     */
    protected $_login;

    /**
     * @var Mage_Core_Model_Cache
     */
    protected $_cache;

    /**
     * @var VinaiKopp_LoginLog_Model_LookupInterface
     */
    protected $_lookup;

    /**
     * @param array $args
     * @param VinaiKopp_LoginLog_Model_Login $login
     * @param Mage_Core_Model_Cache $cache
     * @param VinaiKopp_LoginLog_Model_LookupInterface $lookup
     */
    public function __construct(
        array $args = array(),
        VinaiKopp_LoginLog_Model_Login $login = null,
        Mage_Core_Model_Cache $cache = null,
        VinaiKopp_LoginLog_Model_LookupInterface $lookup = null
    )
    {
        $this->_login = $login;
        $this->_cache = $cache;
        $this->_lookup = $lookup;

        parent::__construct($args);
    }

    /**
     * @return VinaiKopp_LoginLog_Model_Login
     */
    public function getLoginLog()
    {
        if (!$this->_login) {
            // @codeCoverageIgnoreStart
            if (!($model = Mage::registry('current_loginlog'))) {
                $model = Mage::getModel('vinaikopp_loginlog/login');
            }
            $this->_login = $model;
        }
        // @codeCoverageIgnoreEnd
        return $this->_login;
    }

    /**
     * @return Mage_Core_Model_Cache
     */
    public function getCache()
    {
        if (!$this->_cache) {
            // @codeCoverageIgnoreStart
            $this->_cache = Mage::app()->getCacheInstance();
        }
        // @codeCoverageIgnoreEnd
        return $this->_cache;
    }

    public function getLookupModel()
    {
        if (!$this->_lookup) {
            // @codeCoverageIgnoreStart
            $this->_lookup = Mage::getModel('vinaikopp_loginlog/ipInfoDb');
        }
        // @codeCoverageIgnoreEnd
        return $this->_lookup;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        $login = $this->getLoginLog();
        return $login->getIp();
    }

    /**
     * @param string $ip IP4 address
     * @return bool
     */
    public function isValidIp($ip = null)
    {
        if (!$ip) {
            $ip = $this->getIp();
        }

        $parts = explode('.', $ip);
        if (count($parts) != 4) {
            return false;
        }

        foreach ($parts as $part) {
            if (!preg_match('#^\d{1,3}$#', $part)) {
                return false;
            }
            if ($part < 0 || $part > 255) {
                return false;
            }
        }

        if ($parts[0] < 1 || $parts[3] < 1) {
            return false;
        }
        return true;
    }

    /**
     * @param string $ip
     * @return string
     */
    protected function _getCacheIdForIp($ip)
    {
        return md5($ip);
    }

    /**
     * @param string $ip
     * @return mixed|array
     */
    protected function _loadLookupCache($ip)
    {
        $cache = $this->getCache();
        $id = $this->_getCacheIdForIp($ip);
        $data = $cache->load($id);
        if ($data) {
            $data = unserialize($data);
        }
        return $data;
    }

    /**
     * @param string $ip
     * @param array $data
     * @return bool
     */
    public function _saveLookupCache($ip, array $data)
    {
        $cache = $this->getCache();
        $id = $this->_getCacheIdForIp($ip);
        $data = serialize($data);
        return $cache->save(
            $data, $id, 
            array(self::LOOKUP_CACHE_TAG), 3600 * $this->_lookupCacheHours
        );
    }

    /**
     * @param string $ip
     * @return array
     */
    protected function _lookupIp($ip)
    {
        $data = array();
        $lookup = $this->getLookupModel();
        if ($lookup->isLookupAvailable()) {
            $data = $lookup->lookupIp($ip);
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getLookupData()
    {
        $login = $this->getLoginLog();
        $ip = $login->getIp();

        $data = $this->_loadLookupCache($ip);
        if (!$data) {
            $data = $this->_lookupIp($ip);
            $data = $this->_prepareResult($data);
            $reverse = $this->_getReverseLookupResult($ip);
            $data['Reverse DNS'] = $reverse;

            $date = Mage::app()->getLocale()->date(gmdate('U'));

            $data['Lookup Date'] = Mage::helper('core')->formatDate($date, 'short', true);
            $date->add($this->_lookupCacheHours, Zend_Date::TIMES);
            $data['Cached until'] = Mage::helper('core')->formatDate($date, 'short', true);

            $this->_saveLookupCache($ip, $data);
        }


        return $data;
    }

    /**
     * @param string $ip
     * @return string
     */
    protected function _getReverseLookupResult($ip)
    {
        if ($this->isValidIp($ip)) {
            return gethostbyaddr($ip);
        } else {
            return '-';
        }
    }

    /**
     * Ensure all values can be serialized for caching and cleanup array key
     *
     * @param array $arr
     * @return array
     */
    protected function _prepareResult($arr)
    {
        $data = array();
        foreach ($arr as $key => $value) {
            $key = $this->_beautifyLookupKey($key);
            try {
                serialize($value);
            } catch (Exception $e) {
                $value = (string)$value;
            }
            $data[$key] = $value;
        }
        return $data;
    }

    /**
     * Stolen from Varien_Object
     *
     * @param string $key
     * @return string
     * @see Varien_Object::_underscore
     */
    protected function _beautifyLookupKey($key)
    {
        $key = ucwords(preg_replace('/(.)([A-Z])/', "$1 $2", $key));
        return $key;
    }

}