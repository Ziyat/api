<?php

use yii\db\Migration;

/**
 * Handles the creation of table `generic_ratings`.
 */
class m181009_113712_create_generic_ratings_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%generic_ratings}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'generic_product_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%fk-generic_ratings-generic_product_id}}','{{%generic_ratings}}','generic_product_id','{{%generic_products}}','id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%generic_ratings}}');
    }
}
