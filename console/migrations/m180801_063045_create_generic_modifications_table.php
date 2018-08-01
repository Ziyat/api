<?php

use yii\db\Migration;

/**
 * Handles the creation of table `generic_modifications`.
 */
class m180801_063045_create_generic_modifications_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%generic_modifications}}', [
            'id' => $this->primaryKey(),
            'generic_product_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
            'main_photo_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('{{%idx-generic_modifications-value}}', '{{%generic_modifications}}', 'value');
        $this->createIndex('{{%idx-generic_modifications-value-characteristic_id}}', '{{%generic_modifications}}', ['value', 'characteristic_id'], true);
        $this->createIndex('{{%idx-generic_modifications-generic_product_id}}', '{{%generic_modifications}}', 'generic_product_id');

        $this->addForeignKey('{{%fk-generic_modifications-generic_product_id}}', '{{%generic_modifications}}', 'generic_product_id', '{{%generic_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-generic_modifications-characteristic_id}}', '{{%generic_modifications}}', 'characteristic_id', '{{%characteristics}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%generic_modifications}}');
    }
}
