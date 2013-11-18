<?php

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('vinaikopp_configcomments/comment');
if ($installer->getConnection()->isTableExists($tableName)) {
    $installer->getConnection()->dropTable($tableName);
}

$table = $installer->getConnection()->newTable($tableName);
$table->addColumn('comment_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'  => true,
        'identity' => true,
        'unsigned' => true,
        'nullable' => false
    ), 'Primary Key')
    ->addColumn('path', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false
    ), 'Configuration Path')
    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, 1024, array(
        'nullable' => false
    ), 'Comment')
    ->addColumn('author', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        'default'  => ''
    ), 'Comment')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, 5, array(
        'nullable' => false,
        'unsigned' => true,
        'default'  => 0
    ), 'Sort Order')
    ->setComment('Configuration Comments')
    ->addIndex(
        $installer->getIdxName($tableName, array('path', 'sort_order')),
        array('path', 'sort_order')
    )
;
$installer->getConnection()->createTable($table);

$installer->endSetup();