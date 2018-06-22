<?php

use yii\db\Migration;

/**
 * Handles the creation of table `token`.
 */
class m180622_070643_create_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%tokens}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'expired_at' => $this->integer()->notNull(),
        ],$tableOptions);

        $this->createIndex('idx-token-user_id','{{%tokens}}','user_id');

        $this->addForeignKey('fk-token-user_id','{{%tokens}}','user_id','{{%users}}','id','CASCADE','RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tokens}}');
    }
}
