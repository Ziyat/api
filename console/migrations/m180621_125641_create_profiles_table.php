<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profiles`.
 */
class m180621_125641_create_profiles_table extends Migration
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

        $this->createTable('{{%profiles}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->null(),
            'last_name' => $this->string()->null(),
            'date_of_birth' => $this->integer()->null(),
            'photo' => $this->integer()->null(),
        ],$tableOptions);

        $this->createIndex('idx-profiles-user_id','{{%profiles}}','user_id');

        $this->addForeignKey('fk-profiles-user_id','{{%profiles}}','user_id','{{%users}}','id','CASCADE','RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%profiles}}');
    }
}
