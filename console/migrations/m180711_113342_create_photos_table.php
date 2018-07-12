<?php

use yii\db\Migration;

/**
 * Handles the creation of table `photos`.
 */
class m180711_113342_create_photos_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%photos}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-photos-product_id}}', '{{%photos}}', 'product_id');

        $this->addForeignKey('{{%fk-photos-product_id}}', '{{%photos}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%photos}}');
    }
}
