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

class VinaiKopp_LoginLog_Model_System_Config_IpMask
{
    /**
     * @var VinaiKopp_LoginLog_Helper_Data
     */
    protected $_helper;

    /**
     * @param VinaiKopp_LoginLog_Helper_Data $helper
     */
    public function __construct($helper = null)
    {
        if ($helper) {
            $this->_helper = $helper;
        }
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
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = $this->getHelper();
        return array(
            array('value' => 0, 'label' => $helper->__('Disable')),
            array('value' => 1, 'label' => $helper->__('1 Byte 192.168.100.xxx')),
            array('value' => 2, 'label' => $helper->__('2 Bytes 192.168.xxx.xxx')),
            array('value' => 3, 'label' => $helper->__('3 Bytes 192.xxx.xxx.xxx')),

        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toOptionHash()
    {
        $options = array();
        foreach ($this->toOptionArray() as $option) {
            $key = $option['value'];
            $options[$key] = $option['label'];
        }
        return $options;
    }
}
