<?php


/**
 * @method int getId()
 * @method VinaiKopp_LoginLog_Model_Login setId(int $value)
 * @method string getEmail()
 * @method VinaiKopp_LoginLog_Model_Login setEmail(string $value)
 * @method string getUserAgent()
 * @method VinaiKopp_LoginLog_Model_Login setUserAgent(string $value)
 * @method string getLoginAt()
 * @method VinaiKopp_LoginLog_Model_Login setLoginAt(string $value)
 * @method int getCustomerId()
 * @method VinaiKopp_LoginLog_Model_Login setCustomerId(int $value)
 * @method string getIp()
 * @method VinaiKopp_LoginLog_Model_Login setIp(string $value)
 */
class VinaiKopp_LoginLog_Model_Login
    extends Mage_Core_Model_Abstract
{
    /**
     * @var string
     */
    protected $_dateTime;
    
    /**
     * @param string $date
     */
    public function __construct($date = null)
    {
        if ($date) {
            $this->_dateTime = $date;
        }
        parent::__construct();
    }

    /**
     * @return string
     */
    protected function _getCurrentDateTime()
    {
        if (! $this->_dateTime) {
            return Varien_Date::now();
        }
        return $this->_dateTime;
    }

    protected function _construct()
    {
        $this->_init('vinaikopp_loginlog/login');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        if ($this->isObjectNew()) {
            $this->setLoginAt($this->_getCurrentDateTime());
        }
        return parent::_beforeSave();
    }


}