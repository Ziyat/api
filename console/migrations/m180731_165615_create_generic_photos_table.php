<?php

use yii\db\Migration;

/**
 * Handles the creation of table `generic_photos`.
 */
class m180731_165615_create_generic_photos_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%generic_photos}}', [
            'id' => $this->primaryKey(),
            'generic_product_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-generic_photos-generic_product_id}}', '{{%generic_photos}}', 'generic_product_id');

        $this->addForeignKey('{{%fk-generic_photos-generic_product_id}}', '{{%generic_photos}}', 'generic_product_id', '{{%generic_products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%generic_photos}}');
    }
}
