<?php


class VinaiKopp_LoginLog_Model_Resource_Login_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('vinaikopp_loginlog/login');
    }
} 