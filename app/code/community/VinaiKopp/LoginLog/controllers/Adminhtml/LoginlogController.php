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

    public function deleteMassAction()
    {
        $data = $this->getRequest()->getPost('logins');
        if ($data) {
            try {
                $num = 0;
                $model = Mage::getModel('vinaikopp_loginlog/login');
                foreach ((array)$data as $id) {
                    $model->setId($id)->delete();
                    $num++;
                }
                $this->_getSession()->addSuccess(
                    $this->__('Deleted %s login log record(s)', $num)
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                Mage::logException($e);
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Export grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'logins.csv';
        $content    = $this->getLayout()
            ->createBlock('vinaikopp_loginlog/adminhtml_loginLog_list_grid', 'vinaikopp_loginlog.export')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export grid to XML format
     */
    public function exportXmlAction()
    {
        $fileName   = 'logins.xml';
        $content    = $this->getLayout()
            ->createBlock('vinaikopp_loginlog/adminhtml_loginLog_list_grid', 'vinaikopp_loginlog.export')
            ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Display IP reverse DNS and geoip lookup results in a popup
     */
    public function lookupAction()
    {
        $model = Mage::getModel('vinaikopp_loginlog/login');
        if ($id = $this->getRequest()->getParam('id')) {
            $model->load($id);
        }
        Mage::register('current_loginlog', $model);
        
        $this->loadLayout(false);
        $this->renderLayout();
    }
} 