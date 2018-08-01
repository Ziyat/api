<?php

use yii\db\Migration;

/**
 * Handles the creation of table `generic_values`.
 */
class m180731_162835_create_generic_values_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%generic_values}}', [
            'generic_product_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->string(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-generic_values}}', '{{%generic_values}}', ['generic_product_id', 'characteristic_id']);

        $this->createIndex('{{%idx-generic_values-generic_product_id}}', '{{%generic_values}}', 'generic_product_id');
        $this->createIndex('{{%idx-generic_values-characteristic_id}}', '{{%generic_values}}', 'characteristic_id');

        $this->addForeignKey('{{%fk-generic_values-generic_product_id}}', '{{%generic_values}}', 'generic_product_id', '{{%generic_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-generic_values-characteristic_id}}', '{{%generic_values}}', 'characteristic_id', '{{%characteristics}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%generic_values}}');
    }
}
