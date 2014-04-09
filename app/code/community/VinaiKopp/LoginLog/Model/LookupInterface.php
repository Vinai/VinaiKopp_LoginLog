<?php


interface VinaiKopp_LoginLog_Model_LookupInterface
{
    /**
     * @param string $ipAddress
     * @return array
     * @throws Mage_Core_Exception
     */
    public function lookupIp($ipAddress);
} 