<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notifications`.
 */
class m180830_055415_create_notifications_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'from_id' => $this->integer(),
            'type_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%notifications}}');
    }
}
