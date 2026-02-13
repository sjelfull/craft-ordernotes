<?php
/**
 * Order Notes plugin for Craft CMS 3.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\migrations;

use Craft;

use craft\config\DbConfig;
use craft\db\Migration;
use superbig\ordernotes\OrderNotes;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class Install extends Migration
{
    public $driver;
    protected $tableName = '{{%ordernotes}}';

    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    /**
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema($this->tableName);
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                $this->tableName,
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                    'siteId' => $this->integer()->notNull(),
                    'userId' => $this->integer()->null(),
                    'orderId' => $this->integer()->notNull(),
                    'message' => $this->text()->notNull(),
                    'notify' => $this->boolean()->defaultValue(0),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function createIndexes()
    {
        $this->createIndex(
            $this->db->getIndexName(
                $this->tableName,
                'userId',
                false
            ),
            $this->tableName,
            'userId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                $this->tableName,
                'orderId',
                false
            ),
            $this->tableName,
            'orderId',
            false
        );
        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * @return void
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'siteId'),
            $this->tableName,
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'userId'),
            $this->tableName,
            'userId',
            '{{%users}}',
            'id',
            'SET NULL',
            'SET NULL'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'orderId'),
            $this->tableName,
            'orderId',
            '{{%commerce_orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists($this->tableName);
    }
}
