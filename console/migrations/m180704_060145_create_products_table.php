<?php

use yii\db\Migration;

/**
 * Handles the creation of table `products`.
 */
class m180704_060145_create_products_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string()->notNull(),
            'rating' => $this->decimal(3, 2),
            'meta_json' => $this->text(),
        ], $tableOptions);

        $this->createIndex('{{%idx-products-code}}', '{{%products}}', 'code', true);

        $this->createIndex('{{%idx-products-category_id}}', '{{%products}}', 'category_id');
        $this->createIndex('{{%idx-products-brand_id}}', '{{%products}}', 'brand_id');

        $this->addForeignKey('{{%fk-products-category_id}}', '{{%products}}', 'category_id', '{{%categories}}', 'id');
        $this->addForeignKey('{{%fk-products-brand_id}}', '{{%products}}', 'brand_id', '{{%brands}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%products}}');
    }
}
