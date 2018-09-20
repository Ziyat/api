<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shipping_assignments`.
 */
class m180917_121012_create_shipping_assignments_table extends Migration
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
        
        $this->createTable('{{%shipping_assignments}}', [
            'shipping_service_id' => $this->integer(),
            'user_id' => $this->integer(),
            'shipping_free' => $this->boolean()->notNull(),
        ],$tableOptions);

        $this->addPrimaryKey('{{%pk-shipping_assignments}}','{{%shipping_assignments}}',['shipping_service_id','user_id']);

        $this->createIndex('{{%idx-shipping_assignments-shipping_service_id}}','{{%shipping_assignments}}','shipping_service_id');
        $this->createIndex('{{%idx-shipping_assignments-user_id}}','{{%shipping_assignments}}','user_id');


        $this->addForeignKey('{{%fk-location_assignments-shipping_service_id}}','{{%shipping_assignments}}','shipping_service_id','{{%shipping_services}}','id','CASCADE','RESTRICT');
        $this->addForeignKey('{{%fk-location_assignments-user_id}}','{{%shipping_assignments}}','user_id','{{%users}}','id','CASCADE','RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipping_assignments}}');
    }
}
