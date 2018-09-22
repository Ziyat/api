<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shipping_rate_destinations`.
 */
class m180922_120742_create_shipping_rate_destinations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipping_rate_destinations}}', [
            'destination_id' => $this->integer(),
            'rate_id' => $this->integer()
        ]);

        $this->addPrimaryKey('{{%pk-shipping_rate_destinations}}','{{%shipping_rate_destinations}}',['destination_id','rate_id']);
        $this->createIndex('{{%idx-shipping_rate_destinations-destination_id}}','{{%shipping_rate_destinations}}','destination_id');
        $this->createIndex('{{%idx-shipping_rate_destinations-rate_id}}','{{%shipping_rate_destinations}}','rate_id');
        $this->addForeignKey('{{%fk-shipping_rate_destinations-destination_id}}', '{{%shipping_rate_destinations}}', 'destination_id', '{{%countries}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipping_rate_destinations}}');
    }
}
