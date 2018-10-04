<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ratings`.
 */
class m181004_072823_create_ratings_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%ratings}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'score' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%fk-reviews-created_by}}', '{{%ratings}}', 'created_by',
            '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk-reviews-updated_by}}', '{{%ratings}}', 'updated_by',
            '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%ratings}}');
    }
}
