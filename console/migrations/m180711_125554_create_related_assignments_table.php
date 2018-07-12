<?php

use yii\db\Migration;

/**
 * Handles the creation of table `related_assignments`.
 */
class m180711_125554_create_related_assignments_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%related_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'related_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-related_assignments}}', '{{%related_assignments}}', ['product_id', 'related_id']);

        $this->createIndex('{{%idx-related_assignments-product_id}}', '{{%related_assignments}}', 'product_id');
        $this->createIndex('{{%idx-related_assignments-related_id}}', '{{%related_assignments}}', 'related_id');

        $this->addForeignKey('{{%fk-related_assignments-product_id}}', '{{%related_assignments}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-related_assignments-related_id}}', '{{%related_assignments}}', 'related_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%related_assignments}}');
    }
}
