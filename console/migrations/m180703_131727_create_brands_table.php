<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brands`.
 */
class m180703_131727_create_brands_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%brands}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'meta_json' => $this->text(),
        ], $tableOptions);

        $this->createIndex('{{%idx-brands-slug}}', '{{%brands}}', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('{{%brands}}');
    }
}
