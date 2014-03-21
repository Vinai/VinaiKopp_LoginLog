<?php


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