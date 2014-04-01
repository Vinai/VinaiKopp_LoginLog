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

class VinaiKopp_LoginLog_Model_Config_IpMask
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => Mage::helper('vinaikopp_loginlog')->__('Disable')),
            array('value' => 1, 'label' => Mage::helper('vinaikopp_loginlog')->__('1 Byte 192.168.100.xxx')),
            array('value' => 2, 'label' => Mage::helper('vinaikopp_loginlog')->__('2 Bytes 192.168.xxx.xxx')),
            array('value' => 3, 'label' => Mage::helper('vinaikopp_loginlog')->__('3 Bytes 192.xxx.xxx.xxx')),

        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            0 => Mage::helper('vinaikopp_loginlog')->__('Disable'),
            1 => Mage::helper('vinaikopp_loginlog')->__('1 Byte 192.168.100.xxx'),
            2 => Mage::helper('vinaikopp_loginlog')->__('2 Bytes 192.168.xxx.xxx'),
            3 => Mage::helper('vinaikopp_loginlog')->__('3 Bytes 192.xxx.xxx.xxx'),
        );
    }
}
