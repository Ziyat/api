<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category_assignments`.
 */

class m180709_115021_create_category_assignments_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%category_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-category_assignments}}', '{{%category_assignments}}', ['product_id', 'category_id']);

        $this->createIndex('{{%idx-category_assignments-product_id}}', '{{%category_assignments}}', 'product_id');
        $this->createIndex('{{%idx-category_assignments-category_id}}', '{{%category_assignments}}', 'category_id');

        $this->addForeignKey('{{%fk-category_assignments-product_id}}', '{{%category_assignments}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-category_assignments-category_id}}', '{{%category_assignments}}', 'category_id', '{{%categories}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%category_assignments}}');
    }
}
