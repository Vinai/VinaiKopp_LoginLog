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
class VinaiKopp_LoginLog_Model_Observer
{
    /**
     * @var VinaiKopp_LoginLog_Model_Login
     */
    protected $_login;

    /**
     * @var Mage_Core_Helper_Http
     */
    protected $_coreHttpHelper;

    /**
     * @var Mage_Customer_Model_Session
     */
    protected $_customerSession = NULL;

    /**
     * @param VinaiKopp_LoginLog_Model_Login $login
     * @param Mage_Core_Helper_Http          $httpHelper
     * @param Mage_Customer_Model_Session    $customerSession
     */
    public function __construct($login = NULL, $httpHelper = NULL, $customerSession = NULL)
    {
        if (NULL !== $login) {
            $this->_login = $login;
        }
        $this->_coreHttpHelper  = $httpHelper;
        $this->_customerSession = $customerSession;
    }

    /**
     * @return VinaiKopp_LoginLog_Model_Login
     */
    public function getLoginLog()
    {
        if (!$this->_login) {
            // @codeCoverageIgnoreStart
            $this->_login = Mage::getModel('vinaikopp_loginlog/login');
        }
        // @codeCoverageIgnoreEnd
        return $this->_login;
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

    /**
     * @param Varien_Event_Observer $args
     */
    public function customerLogin(Varien_Event_Observer $args)
    {
        /** @var Mage_Customer_Model_Customer $customer */
        $customer = $args->getCustomer();
        $helper   = $this->getCoreHttpHelper();
        $this->getLoginLog()
            ->setCustomerId($customer->getId())
            ->setIp($helper->getRemoteAddr())
            ->setEmail($customer->getEmail())
            ->setUserAgent($helper->getHttpUserAgent())
            ->save();
    }

    /**
     * @param Varien_Event_Observer $args
     *
     * @return null
     */
    public function customerLogout(Varien_Event_Observer $args)
    {
        $logId = (int)$this->getSession()->getVinaiKoppLoginLogId();
        if (0 === $logId) {
            return NULL;
        }
        $this->getLoginLog()
            ->load($logId)
            ->setLoggedOutAt(Varien_Date::now())
            ->save();
        return NULL;
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    public function getSession()
    {
        if (NULL === $this->_customerSession) {
            $this->_customerSession = Mage::getSingleton('customer/session');
        }
        return $this->_customerSession;
    }
}