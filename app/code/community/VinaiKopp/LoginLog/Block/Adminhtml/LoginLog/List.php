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


class VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List 
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_blockGroup = 'vinaikopp_loginlog';
    protected $_controller = 'adminhtml_loginLog_list';

    protected function _prepareLayout()
    {
        $this->_headerText = $this->__('Customer Logins');
        parent::_prepareLayout();
        $this->_removeButton('add');
        return $this;
    }


} 