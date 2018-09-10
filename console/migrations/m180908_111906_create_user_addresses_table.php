<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_addresses`.
 */
class m180908_111906_create_user_addresses_table extends Migration
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
        $this->createTable('{{%user_addresses}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'country_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'phone' => $this->string(),
            'address_line_1' => $this->string()->notNull(),
            'address_line_2' => $this->string()->null(),
            'city' => $this->string()->notNull(),
            'state' => $this->string(),
            'index' => $this->string()->notNull(),
            'default' => $this->boolean()->notNull()->defaultValue(0),
        ],$tableOptions);

        $this->createIndex('{{%idx-user_addresses-user_id}}','{{%user_addresses}}','user_id');
        $this->createIndex('{{%idx-user_addresses-country_id}}','{{%user_addresses}}','country_id');

        $this->addForeignKey('{{%fk-user_addresses-user_id}}','{{%user_addresses}}','user_id','{{%users}}','id','CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_addresses-country_id}}','{{%user_addresses}}','country_id','countries','id','CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_addresses}}');
    }
}
