<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notifications`.
 */
class m180830_055415_create_notifications_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('notifications', [
            'id' => $this->primaryKey(),
            'type' => $this->integer(),
            'from_id' => $this->integer(),
            'type_id' => $this->integer(),
            'created_at' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('notifications');
    }
}
