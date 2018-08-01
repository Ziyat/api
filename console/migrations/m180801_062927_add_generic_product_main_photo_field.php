<?php

use yii\db\Migration;

/**
 * Class m180801_062927_add_generic_product_main_photo_field
 */
class m180801_062927_add_generic_product_main_photo_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%generic_products}}', 'main_photo_id', $this->integer());

        $this->createIndex('{{%idx-generic_products-main_photo_id}}', '{{%generic_products}}', 'main_photo_id');

        $this->addForeignKey('{{%fk-generic_products-main_photo_id}}', '{{%generic_products}}', 'main_photo_id', '{{%generic_photos}}', 'id', 'SET NULL', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-generic_products-main_photo_id}}', '{{%generic_products}}');

        $this->dropColumn('{{%generic_products}}', 'main_photo_id');
    }
}
