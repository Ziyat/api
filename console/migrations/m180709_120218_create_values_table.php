<?php

use yii\db\Migration;

/**
 * Handles the creation of table `values`.
 */
class m180709_120218_create_values_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%values}}', [
            'product_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->string(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-values}}', '{{%values}}', ['product_id', 'characteristic_id']);

        $this->createIndex('{{%idx-values-product_id}}', '{{%values}}', 'product_id');
        $this->createIndex('{{%idx-values-characteristic_id}}', '{{%values}}', 'characteristic_id');

        $this->addForeignKey('{{%fk-values-product_id}}', '{{%values}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-values-characteristic_id}}', '{{%values}}', 'characteristic_id', '{{%characteristics}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%values}}');
    }
}
