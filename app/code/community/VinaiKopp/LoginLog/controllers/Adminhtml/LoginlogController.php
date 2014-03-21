<?php

class VinaiKopp_LoginLog_Adminhtml_LoginlogController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        $session = Mage::getSingleton('admin/session');
        $result = $session->isAllowed('customer/login_log');
        return $result;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout(false);
        $this->renderLayout();
    }
} 