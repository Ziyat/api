<?php

use yii\db\Migration;

/**
 * Handles the creation of table `reviews`.
 */
class m180928_093330_create_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%reviews}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'text' => $this->text(),
            'item_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'score' => $this->integer()->defaultValue(0),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%fk-reviews-created_by}}', '{{%reviews}}', 'created_by', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->insert('{{%reviews}}', [
            'id' => 1,
            'item_id' => 1,
            'type' => 1,
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
            'created_at' => 1,
            'updated_at' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reviews}}');
    }
}
