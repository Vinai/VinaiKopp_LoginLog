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
class VinaiKopp_LoginLog_Model_Resource_Login_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('vinaikopp_loginlog/login');
    }

    /**
     * @return $this
     */
    public function addDuration()
    {
        // @fixme: use resource helper instead of MySQL specific SQL
        $this->addExpressionFieldToSelect('duration', 'TIMEDIFF({{logout_at}},{{login_at}})', array(
            'login_at'  => 'login_at',
            'logout_at' => 'logout_at',
        ));
        return $this;
    }
}