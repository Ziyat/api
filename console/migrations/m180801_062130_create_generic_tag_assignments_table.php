<?php

use yii\db\Migration;

/**
 * Handles the creation of table `generic_tag_assignments`.
 */
class m180801_062130_create_generic_tag_assignments_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%generic_tag_assignments}}', [
            'generic_product_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-generic_tag_assignments}}', '{{%generic_tag_assignments}}', ['generic_product_id', 'tag_id']);

        $this->createIndex('{{%idx-generic_tag_assignments-generic_product_id}}', '{{%generic_tag_assignments}}', 'generic_product_id');
        $this->createIndex('{{%idx-generic_tag_assignments-tag_id}}', '{{%generic_tag_assignments}}', 'tag_id');

        $this->addForeignKey('{{%fk-generic_tag_assignments-generic_product_id}}', '{{%generic_tag_assignments}}', 'generic_product_id', '{{%generic_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-generic_tag_assignments-tag_id}}', '{{%generic_tag_assignments}}', 'tag_id', '{{%tags}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%generic_tag_assignments}}');
    }
}
