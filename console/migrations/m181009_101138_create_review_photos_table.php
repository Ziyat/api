<?php

use yii\db\Migration;

/**
 * Handles the creation of table `review_photos`.
 */
class m181009_101138_create_review_photos_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%review_photos}}', [
            'id' => $this->primaryKey(),
            'review_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-review_photos-review_id}}', '{{%review_photos}}', 'review_id');

        $this->addForeignKey('{{%fk-review_photos-review_id}}', '{{%review_photos}}', 'review_id', '{{%reviews}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%review_photos}}');
    }
}
