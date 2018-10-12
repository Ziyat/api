<?php

use yii\db\Migration;

/**
 * Handles the creation of table `push_tokens`.
 */
class m181011_123509_create_push_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%push_tokens}}', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->notNull(),
            'service' => $this->string(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-push_tokens-user_id}}', '{{%push_tokens}}', 'user_id');
        $this->addForeignKey('{{%fk-push_tokens-user_id}}', '{{%push_tokens}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%push_tokens}}');
    }
}
