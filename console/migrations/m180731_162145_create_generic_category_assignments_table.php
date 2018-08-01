<?php

use yii\db\Migration;

/**
 * Handles the creation of table `generic_category_assignments`.
 */
class m180731_162145_create_generic_category_assignments_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%generic_category_assignments}}', [
            'generic_product_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-generic_category_assignments}}', '{{%generic_category_assignments}}', ['generic_product_id', 'category_id']);

        $this->createIndex('{{%idx-generic_category_assignments-generic_product_id}}', '{{%generic_category_assignments}}', 'generic_product_id');
        $this->createIndex('{{%idx-generic_category_assignments-category_id}}', '{{%generic_category_assignments}}', 'category_id');

        $this->addForeignKey('{{%fk-generic_category_assignments-generic_product_id}}', '{{%generic_category_assignments}}', 'generic_product_id', '{{%generic_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-generic_category_assignments-category_id}}', '{{%generic_category_assignments}}', 'category_id', '{{%categories}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%generic_category_assignments}}');
    }
}
