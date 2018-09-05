<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notitication_assignments`.
 */
class m180904_100628_create_notification_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification_assignments}}', [
            'to_id' => $this->integer()->notNull(),
            'notification_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey(
            '{{%pk-notification_assignments}}',
            '{{%notification_assignments}}',
            ['to_id', 'notification_id']
        );

        $this->createIndex('{{%idx-notification_assignments-to_id}}', '{{%notification_assignments}}', 'to_id');

        $this->createIndex('{{%idx-notification_assignments-notification_id}}', '{{%notification_assignments}}', 'notification_id');

        $this->addForeignKey('{{%fk-notification_assignments-to_id}}', '{{%notification_assignments}}', 'to_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');

        $this->addForeignKey('{{%fk-notification_assignments-notification_id}}', '{{%notification_assignments}}', 'notification_id', '{{%notifications}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notification_assignments}}');
    }
}
