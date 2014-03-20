<?php


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
     * @param VinaiKopp_LoginLog_Model_Login $login
     * @param Mage_Core_Helper_Http $httpHelper
     */
    public function __construct($login = null, $httpHelper = null)
    {
        if ($login) {
            $this->_login = $login;
        }
        
        $this->_coreHttpHelper = $httpHelper;
    }

    /**
     * @return VinaiKopp_LoginLog_Model_Login
     */
    public function getLoginLog()
    {
        if (!$this->_login) {
            $this->_login = Mage::getModel('vinaikopp_loginlog/login');
        }
        return $this->_login;
    }

    /**
     * @return Mage_Core_Helper_Http
     */
    public function getCoreHttpHelper()
    {
        if (!$this->_coreHttpHelper) {
            $this->_coreHttpHelper = Mage::helper('core/http');
        }
        return $this->_coreHttpHelper;
    }

    /**
     * @param Varien_Event_Observer $args
     */
    public function customerLogin(Varien_Event_Observer $args)
    {
        /** @var Mage_Customer_Model_Customer $customer */
        $customer = $args->getCustomer();
        $this->getLoginLog()
            ->setCustomerId($customer->getId())
            ->setIp($this->getCoreHttpHelper()->getRemoteAddr())
            ->setEmail($customer->getEmail())
            ->setUserAgent($this->getCoreHttpHelper()->getHttpUserAgent())
            ->save();    
    }
} 