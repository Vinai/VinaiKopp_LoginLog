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
class VinaiKopp_LoginLog_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    /**
     * @var Mage_Core_Model_Store
     */
    protected $_store;

    /**
     * @param Mage_Core_Model_Store $store
     */
    public function __construct(Mage_Core_Model_Store $store = null)
    {
        $this->_store = $store;
    }

    /**
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        if (! $this->_store) {
            // @codeCoverageIgnoreStart
            $this->_store = Mage::app()->getStore();
        }
        // @codeCoverageIgnoreEnd
        return $this->_store;
    }
    
    /**
     * @param string $ipAddress
     *
     * @return mixed
     */
    public function maskIpAddress($ipAddress)
    {
        if (false === $this->_isIP($ipAddress)) {
            return $ipAddress;
        }

        $maskConfig = (int)$this->getStore()->getConfig('vinaikopp_loginlog/settings/mask_ip_address');
        if (0 === $maskConfig) {
            return $ipAddress;
        }
        $ipParts = array_reverse(explode('.', $ipAddress));
        for ($i = 0; $i < $maskConfig; $i++) {
            $ipParts[$i] = 'xxx';
        }
        return implode('.', array_reverse($ipParts));
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    protected function _isIp($ip)
    {
        return false !== filter_var($ip, FILTER_VALIDATE_IP);
    }
}