<?php

use yii\db\Migration;

/**
 * Handles the creation of table `carousel_images`.
 */
class m180810_083122_create_carousel_images_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%carousel_images}}', [
            'id' => $this->primaryKey(),
            'carousel_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ],$tableOptions);

        $this->createIndex('{{%idx-carousel_images-carousel_id}}', '{{%carousel_images}}', 'carousel_id');
        $this->addForeignKey('{{%fk-carousel_images-carousel_id}}', '{{%carousel_images}}', 'carousel_id', '{{%carousels}}', 'id', 'CASCADE', 'RESTRICT');
    }
    public function down()
    {
        $this->dropIndex('{{%idx-carousel_images-carousel_id}}','{{%carousel_images}}');
        $this->dropForeignKey('{{%fk-carousel_images-carousel_id}}','{{%carousel_images}}');
        $this->dropTable('{{%carousel_images}}');
    }
}
