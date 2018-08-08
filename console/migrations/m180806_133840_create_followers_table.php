<?php

use yii\db\Migration;

/**
 * Handles the creation of table `followers`.
 */
class m180806_133840_create_followers_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%followers}}', [
            'user_id' => $this->integer(),
            'follower_id' => $this->integer(),
            'created_at' => $this->integer(),
            'status' => $this->integer()->defaultValue(1),
        ],$tableOptions);

        $this->addPrimaryKey('{{%pk-followers}}', '{{%followers}}', ['user_id', 'follower_id']);

        $this->createIndex('{{%idx-followers-user_id}}', '{{%followers}}', 'user_id');
        $this->createIndex('{{%idx-followers-follower_id}}', '{{%followers}}', 'follower_id');

        $this->addForeignKey('{{%fk-followers-user_id}}', '{{%followers}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-followers-follower_id}}', '{{%followers}}', 'follower_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropPrimaryKey('{{%pk-followers}}','{{%followers}}');

        $this->dropIndex('{{%idx-followers-user_id}}','{{%followers}}');
        $this->dropIndex('{{%idx-followers-follower_id}}','{{%followers}}');

        $this->dropForeignKey('{{%fk-followers-user_id}}','{{%followers}}');
        $this->dropForeignKey('{{%fk-followers-follower_id}}','{{%followers}}');

        $this->dropTable('{{%followers}}');
    }
}
