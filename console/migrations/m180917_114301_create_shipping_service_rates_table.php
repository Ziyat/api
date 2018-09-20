<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shipping_service_rates`.
 */
class m180917_114301_create_shipping_service_rates_table extends Migration
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

        $this->createTable('{{%shipping_service_rates}}', [
            'id' => $this->primaryKey(),
            'shipping_service_id' => $this->integer()->notNull(),
            'price_type' => $this->integer()->notNull(),
            'price_min' => $this->float()->null(),
            'price_max' => $this->float()->null(),
            'price_fix' => $this->float()->null(),
            'day_min' => $this->integer()->null(),
            'day_max' => $this->integer()->null(),
            'country_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
        ]);

        $this->createIndex('{{%idx-shipping_service_rates-shipping_service_id}}','{{%shipping_service_rates}}','shipping_service_id');
        $this->createIndex('{{%idx-shipping_service_rates-country_id}}','{{%shipping_service_rates}}','country_id');

        $this->addForeignKey('{{%fk-shipping_service_rates-shipping_service_id}}','{{%shipping_service_rates}}','shipping_service_id','{{%shipping_services}}','id','CASCADE','RESTRICT');
        $this->addForeignKey('{{%fk-shipping_service_rates-country_id}}','{{%shipping_service_rates}}','country_id','{{%countries}}','id','CASCADE','RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipping_service_rates}}');
    }
}
