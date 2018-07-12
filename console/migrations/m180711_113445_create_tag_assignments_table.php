<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag_assignments`.
 */
class m180711_113445_create_tag_assignments_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%tag_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-tag_assignments}}', '{{%tag_assignments}}', ['product_id', 'tag_id']);

        $this->createIndex('{{%idx-tag_assignments-product_id}}', '{{%tag_assignments}}', 'product_id');
        $this->createIndex('{{%idx-tag_assignments-tag_id}}', '{{%tag_assignments}}', 'tag_id');

        $this->addForeignKey('{{%fk-tag_assignments-product_id}}', '{{%tag_assignments}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-tag_assignments-tag_id}}', '{{%tag_assignments}}', 'tag_id', '{{%tags}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%tag_assignments}}');
    }
}
