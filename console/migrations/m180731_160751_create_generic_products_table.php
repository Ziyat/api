<?php
use yii\db\Migration;

/**
 * Handles the creation of table `generic_products`.
 */
class m180731_160751_create_generic_products_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%generic_products}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'rating' => $this->decimal(3, 2),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-generic_products-category_id}}', '{{%generic_products}}', 'category_id');
        $this->createIndex('{{%idx-generic_products-brand_id}}', '{{%generic_products}}', 'brand_id');

        $this->createIndex('{{%idx-generic_products-created_by}}', '{{%generic_products}}', 'created_by');

        $this->addForeignKey('{{%fk-generic_products-category_id}}', '{{%generic_products}}', 'category_id', '{{%categories}}', 'id');
        $this->addForeignKey('{{%fk-generic_products-brand_id}}', '{{%generic_products}}', 'brand_id', '{{%brands}}', 'id');

        $this->addForeignKey('fk-generic_products-created_by','{{%generic_products}}','created_by','{{%users}}','id','RESTRICT','RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%generic_products}}');
    }
}
