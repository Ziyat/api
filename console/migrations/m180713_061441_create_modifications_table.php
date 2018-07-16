<?php

use yii\db\Migration;

/**
 * Handles the creation of table `modifications`.
 */
class m180713_061441_create_modifications_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%modifications}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
            'price' => $this->float(),
            'main_photo_id' => $this->integer(),
            'quantity' => $this->integer()
        ], $tableOptions);

        $this->createIndex('{{%idx-modifications-value}}', '{{%modifications}}', 'value');
        $this->createIndex('{{%idx-modifications-value-characteristic_id}}', '{{%modifications}}', ['value', 'characteristic_id'], true);
        $this->createIndex('{{%idx-modifications-product_id}}', '{{%modifications}}', 'product_id');

        $this->addForeignKey('{{%fk-modifications-product_id}}', '{{%modifications}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-modifications-characteristic_id}}', '{{%modifications}}', 'characteristic_id', '{{%characteristics}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%modifications}}');
    }
}
