<?php


class VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _construct()
    {
        $this->setId('vinaikopp_loginlog');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        return parent::_construct();
    }

    /**
     * Return Grid URL for AJAX query
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * Prepare collection
     *
     * @return VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('vinaikopp_loginlog/login_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }
    /**
     * Prepare grid columns
     *
     * @return VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'id',
            'type' => 'number'
        ));
        
        $this->addColumn('login_at', array(
            'header' => $this->__('Date'),
            'index' => 'login_at',
            'type' => 'datetime'
        ));

        $this->addColumn('email', array(
            'header' => $this->__('Customer Email'),
            'index' => 'email',
        ));

        $this->addColumn('ip', array(
            'header' => $this->__('IP'),
            'index' => 'ip',
        ));

        $this->addColumn('user_agent', array(
            'header' => $this->__('User Agent'),
            'index' => 'user_agent',
            'renderer' => 'adminhtml/widget_grid_column_renderer_longtext',
            'string_limit' => 150,
            'escape' => true,
        ));
        
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportXml', $this->__('Excel XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('logins');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/deleteMass')
        ));

        return parent::_prepareMassaction();
    }
} 