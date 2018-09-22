<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shipping_assignments`.
 */
class m180920_130924_create_shipping_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipping_assignments}}', [
            'product_id' => $this->integer(),
            'rate_id' => $this->integer(),
            'free_shipping_type' => $this->integer(),
            'price' => $this->float(),
            'countries' => $this->text(),
        ]);

        $this->addPrimaryKey('{{%pk-shipping_assignments}}','{{%shipping_assignments}}',['product_id','rate_id']);
        $this->createIndex('{{%idx-shipping_assignments-product_id}}','{{%shipping_assignments}}','product_id');
        $this->createIndex('{{%idx-shipping_assignments-rate_id}}','{{%shipping_assignments}}','rate_id');

        $this->addForeignKey(
            '{{%fk-shipping_assignments-product_id}}',
            '{{%shipping_assignments}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk-shipping_assignments-rate_id}}',
            '{{%shipping_assignments}}',
            'rate_id',
            '{{%shipping_service_rates}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('shipping_assignments');
    }
}
