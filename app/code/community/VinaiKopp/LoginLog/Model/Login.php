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
 * @method int getCustomerId()
 * @method VinaiKopp_LoginLog_Model_Login setCustomerId(int $value)
 * @method string getLogoutAt()
 * @method VinaiKopp_LoginLog_Model_Login setLogoutAt(string $value)
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
     * @var VinaiKopp_LoginLog_Helper_Data
     */
    protected $_helper;

    /**
     * @var Mage_Customer_Model_Session
     */
    protected $_session;

    /**
     * @var Mage_Core_Helper_Http
     */
    protected $_coreHttpHelper;

    /**
     * @param string $date
     * @param VinaiKopp_LoginLog_Helper_Data $helper
     * @param Mage_Customer_Model_Session $session
     * @param Mage_Core_Helper_Http $httpHelper
     */
    public function __construct(
        $date = null,
        VinaiKopp_LoginLog_Helper_Data $helper = null,
        Mage_Customer_Model_Session $session = null,
        Mage_Core_Helper_Http $httpHelper = null)
    {
        if ($date) {
            $this->_dateTime = $date;
        }
        $this->_helper = $helper;
        $this->_session = $session;
        $this->_coreHttpHelper = $httpHelper;
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

    /**
     * @return VinaiKopp_LoginLog_Helper_Data
     */
    public function getHelper()
    {
        if (! $this->_helper) {
            // @codeCoverageIgnoreStart
            $this->_helper = Mage::helper('vinaikopp_loginlog');
        }
        // @codeCoverageIgnoreEnd
        return $this->_helper;
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    public function getSession()
    {
        if (! $this->_session) {
            // @codeCoverageIgnoreStart
            $this->_session = Mage::getSingleton('customer/session');
        }
        // @codeCoverageIgnoreEnd
        return $this->_session;
    }

    /**
     * @return Mage_Core_Helper_Http
     */
    public function getCoreHttpHelper()
    {
        if (!$this->_coreHttpHelper) {
            // @codeCoverageIgnoreStart
            $this->_coreHttpHelper = Mage::helper('core/http');
        }
        // @codeCoverageIgnoreEnd
        return $this->_coreHttpHelper;
    }

    protected function _construct()
    {
        $this->_init('vinaikopp_loginlog/login');
    }

    /**
     * @param string $ip
     * @return string
     */
    protected function _maskIpAddress($ip)
    {
        $helper = $this->getHelper();
        return $helper->maskIpAddress($ip);
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $ip = $this->getData('ip');
        $this->setData('ip', $this->_maskIpAddress($ip));

        return parent::_beforeSave();
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function afterCommitCallback()
    {
        $this->getSession()->setData('vinaikopp_loginlog_id', $this->getId());
        return parent::afterCommitCallback();
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @return bool true
     */
    public function registerLogin(Mage_Customer_Model_Customer $customer)
    {
        $helper = $this->getCoreHttpHelper();
        $this->setCustomerId($customer->getId())
            ->setEmail($customer->getEmail())
            ->setIp($helper->getRemoteAddr())
            ->setUserAgent($helper->getHttpUserAgent())
            ->setLoginAt($this->_getCurrentDateTime());
        return true;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @return bool
     */
    public function registerLogout(Mage_Customer_Model_Customer $customer)
    {
        $id = $this->getSession()->getData('vinaikopp_loginlog_id');
        if ($id) {
            $this->load($id);
            if ($this->getCustomerId() === $customer->getId()) {
                $this->setLogoutAt($this->_getCurrentDateTime());
                return true;
            }
        }
        return false;
    }
}