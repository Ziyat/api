<?php

use yii\db\Migration;

class m180703_112909_create_tags_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-tags-slug}}', '{{%tags}}', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('{{%tags}}');
    }
}