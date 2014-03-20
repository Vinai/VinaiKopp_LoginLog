<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();


$tableName = $installer->getTable('vinaikopp_loginlog/login_log');

if ($installer->tableExists($tableName)) {
    $installer->getConnection()->dropTable($tableName);
}

/** @var $ddlTable Varien_Db_Ddl_Table */
$ddlTable = $installer->getConnection()->newTable($tableName);

$ddlTable->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'primary'  => true,
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
), 'Primary Key')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'nullable' => false,
        'unsigned' => true
    ), 'Customer ID')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        'default'  => ''
    ), 'Customer Email')
    ->addColumn('ip', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        'default'  => ''
    ), 'Login IP')
    ->addColumn('user_agent', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        'default'  => ''
    ), 'User Agent Header')
    ->addColumn('login_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
    ), 'Login Date and Time')
    ->addIndex(
        $installer->getIdxName($tableName, array('customer_id')),
        array('customer_id')
    )
    ->addForeignKey(
        $installer->getFkName($tableName, 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id', $installer->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Customer Login Log Table');

$installer->getConnection()->createTable($ddlTable);


$installer->endSetup();
