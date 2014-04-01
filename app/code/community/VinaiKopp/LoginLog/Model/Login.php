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

/**
 * @method int getId()
 * @method VinaiKopp_LoginLog_Model_Login setId(int $value)
 * @method string getEmail()
 * @method VinaiKopp_LoginLog_Model_Login setEmail(string $value)
 * @method string getUserAgent()
 * @method VinaiKopp_LoginLog_Model_Login setUserAgent(string $value)
 * @method string getLoginAt()
 * @method VinaiKopp_LoginLog_Model_Login setLoginAt(string $value)
 * @method string getLoggedOutAt()
 * @method VinaiKopp_LoginLog_Model_Login setLoggedOutAt(string $value)
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
    public function __construct($date = NULL)
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
        if (!$this->_dateTime) {
            // @codeCoverageIgnoreStart
            return Varien_Date::now();
        }
        // @codeCoverageIgnoreEnd
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

        $this->setData('ip', Mage::helper('vinaikopp_loginlog')->maskIPAddress($this->getData('ip')));

        return parent::_beforeSave();
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function afterCommitCallback()
    {
        Mage::getSingleton('customer/session')->setVinaiKoppLoginLogId($this->getId());
        return parent::afterCommitCallback();
    }
}