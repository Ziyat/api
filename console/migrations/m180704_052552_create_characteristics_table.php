<?php

use yii\db\Migration;

/**
 * Handles the creation of table `characteristics`.
 */
class m180704_052552_create_characteristics_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%characteristics}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => $this->string(16)->notNull(),
            'required' => $this->boolean()->notNull(),
            'default' => $this->string(),
            'variants_json' => $this->text(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%characteristics}}');
    }
}
