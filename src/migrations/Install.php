<?php
/**
 * Order Notes plugin for Craft CMS 5.x
 *
 * Order notes for Commerce
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\ordernotes\migrations;

use Craft;
use craft\db\Migration;

/**
 * @author    Superbig
 * @package   OrderNotes
 * @since     2.0.0
 */
class Install extends Migration
{
    protected string $tableName = '{{%ordernotes}}';

    public function safeUp(): bool
    {
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTableIfExists($this->tableName);

        return true;
    }

    protected function createTables(): bool
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema($this->tableName);
        if ($tableSchema !== null) {
            return false;
        }

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
                'notify' => $this->boolean()->defaultValue(false),
            ]
        );

        return true;
    }

    protected function createIndexes(): void
    {
        $this->createIndex(
            null,
            $this->tableName,
            'userId',
            false
        );

        $this->createIndex(
            null,
            $this->tableName,
            'orderId',
            false
        );
    }

    protected function addForeignKeys(): void
    {
        $this->addForeignKey(
            null,
            $this->tableName,
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            null,
            $this->tableName,
            'userId',
            '{{%users}}',
            'id',
            'SET NULL',
            'SET NULL'
        );

        $this->addForeignKey(
            null,
            $this->tableName,
            'orderId',
            '{{%commerce_orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
}
