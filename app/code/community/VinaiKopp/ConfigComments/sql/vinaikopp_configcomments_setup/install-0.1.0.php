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
 * @package    VinaiKopp_ConfigComments
 * @copyright  Copyright (c) 2014 Vinai Kopp http://netzarbeiter.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


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