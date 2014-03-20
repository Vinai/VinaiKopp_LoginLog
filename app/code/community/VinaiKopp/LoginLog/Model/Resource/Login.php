<?php


class VinaiKopp_LoginLog_Model_Resource_Login
    extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('vinaikopp_loginlog/login_log', 'id');
    }
} 