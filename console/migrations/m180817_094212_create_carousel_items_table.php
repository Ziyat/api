<?php

use yii\db\Migration;

/**
 * Handles the creation of table `carousel_items`.
 */
class m180817_094212_create_carousel_items_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%carousel_items}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->string(),
            'text' => $this->text(),
            'item_id' => $this->integer()->notNull(),
            'carousel_id' => $this->integer()->notNull(),
        ],$tableOptions);


        $this->createIndex('{{%idx-carousel_items-carousel_id}}', '{{%carousel_items}}', 'carousel_id');

        $this->addForeignKey('{{%fk-carousel_items-carousel_id}}', '{{%carousel_items}}', 'carousel_id', '{{%carousels}}', 'id', 'CASCADE', 'RESTRICT');


        $this->dropColumn('{{%carousels}}','description');
        $this->dropColumn('{{%carousels}}','item_id');
        $this->renameColumn('{{%carousels}}','appointment','template_id');
        $this->dropColumn('{{%carousels}}','text');
        $this->addColumn('{{%carousels}}','status', $this->boolean()->defaultValue(0));

        $this->dropForeignKey('{{%fk-carousel_images-carousel_id}}','{{%carousel_images}}');
        $this->dropIndex('{{%idx-carousel_images-carousel_id}}','{{%carousel_images}}');

        $this->renameColumn('{{%carousel_images}}','carousel_id','carousel_item_id');

        $this->createIndex('{{%idx-carousel_images-carousel_item_id}}', '{{%carousel_images}}', 'carousel_item_id');

        $this->addForeignKey('{{%fk-carousel_images-carousel_item_id}}', '{{%carousel_images}}', 'carousel_item_id', '{{%carousel_items}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%carousel_items}}');

        $this->addColumn('{{%carousels}}','description',$this->string());
        $this->addColumn('{{%carousels}}','item_id',$this->integer());
        $this->addColumn('{{%carousels}}','appointment',$this->integer());
    }
}
