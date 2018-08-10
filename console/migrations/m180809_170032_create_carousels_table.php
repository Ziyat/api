<?php

use yii\db\Migration;

/**
 * Handles the creation of table `carousels`.
 */
class m180809_170032_create_carousels_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%carousels}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->integer(),
            'text' => $this->text(),
            'type' => $this->integer(),
            'item_id' => $this->integer(),
            'appointment' => $this->integer(),
        ],$tableOptions);

       }

    /**
     * {@inheritdoc}
     */
    public function down()
    {

        $this->dropTable('{{%carousels}}');
    }
}
