<?php

use yii\db\Migration;

/**
 * Handles the creation of table `price_fix`.
 */
class m180714_162554_create_prices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%prices}}', [
            'id' => $this->primaryKey(),
            'cur_price' => $this->float()->notNull(),
            'end_price' => $this->float(),
            'max_price' => $this->float(),
            'deadline' => $this->integer(),
            'product_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull()
        ],$tableOptions);

        $this->createIndex('{{%idx-prices-product_id}}', '{{%prices}}', 'product_id');
        $this->addForeignKey('{{%fk-prices-product_id}}', '{{%prices}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%prices}}');
    }
}
