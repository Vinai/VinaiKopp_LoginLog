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
} 