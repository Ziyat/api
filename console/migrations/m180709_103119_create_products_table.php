<?php

use yii\db\Migration;

/**
 * Handles the creation of table `products`.
 */
class m180709_103119_create_products_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'price_type' => $this->string(16)->notNull(),
            'rating' => $this->decimal(3, 2),
            'meta_json' => $this->text(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-products-category_id}}', '{{%products}}', 'category_id');
        $this->createIndex('{{%idx-products-brand_id}}', '{{%products}}', 'brand_id');

        $this->createIndex('{{%idx-products-created_by}}', '{{%products}}', 'created_by');

        $this->addForeignKey('{{%fk-products-category_id}}', '{{%products}}', 'category_id', '{{%categories}}', 'id');
        $this->addForeignKey('{{%fk-products-brand_id}}', '{{%products}}', 'brand_id', '{{%brands}}', 'id');

        $this->addForeignKey('fk-products-created_by','{{%products}}','created_by','{{%users}}','id','RESTRICT','RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%products}}');
    }
}
