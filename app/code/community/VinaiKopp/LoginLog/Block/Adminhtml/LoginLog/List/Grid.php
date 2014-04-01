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
class VinaiKopp_LoginLog_Block_Adminhtml_LoginLog_List_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _construct()
    {
        $this->setId('vinaikopp_loginlog');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        parent::_construct();
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
        $collection->addDuration();
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
            'header'   => $this->__('ID'),
            'sortable' => true,
            'width'    => '40px',
            'index'    => 'id',
            'type'     => 'number'
        ));

        $this->addColumn('login_at', array(
            'header' => $this->__('Date'),
            'index'  => 'login_at',
            'type'   => 'datetime'
        ));

        $this->addColumn('logged_out_at', array(
            'header' => $this->__('Logout'),
            'index'  => 'logged_out_at',
            'type'   => 'datetime'
        ));

        $this->addColumn('duration', array(
            'header' => $this->__('Duration (hh:mm:ss)'),
            'index'  => 'duration',
        ));

        $this->addColumn('email', array(
            'header' => $this->__('Customer Email'),
            'index'  => 'email',
        ));

        $this->addColumn('ip', array(
            'header' => $this->__('IP'),
            'index'  => 'ip',
        ));

        $this->addColumn('user_agent', array(
            'header'       => $this->__('User Agent'),
            'index'        => 'user_agent',
            'renderer'     => 'adminhtml/widget_grid_column_renderer_longtext',
            'string_limit' => 150,
            'escape'       => true,
        ));

        if (!$this->_isExport) {
            $this->addColumn('action',
                array(
                    'header'   => $this->__('Action'),
                    'width'    => '150px',
                    'type'     => 'action',
                    'getter'   => 'getId',
                    'actions'  => array(
                        array(
                            'caption' => Mage::helper('catalog')->__('Lookup'),
                            'url'     => array(
                                'base'   => '*/*/lookup',
                                'params' => array()
                            ),
                            'field'   => 'id'
                        )
                    ),
                    'filter'   => false,
                    'sortable' => false
                ));
        }

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
            'url'   => $this->getUrl('*/*/deleteMass')
        ));

        return parent::_prepareMassaction();
    }

    /**
     * @param Mage_Catalog_Model_Product|Varien_Object
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/customer/edit', array('id' => $item->getCustomerId()));
    }
}