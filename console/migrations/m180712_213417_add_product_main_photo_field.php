<?php

use yii\db\Migration;

/**
 * Class m180712_213417_add_product_main_photo_field
 */
class m180712_213417_add_product_main_photo_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%products}}', 'main_photo_id', $this->integer());

        $this->createIndex('{{%idx-products-main_photo_id}}', '{{%products}}', 'main_photo_id');

        $this->addForeignKey('{{%fk-products-main_photo_id}}', '{{%products}}', 'main_photo_id', '{{%photos}}', 'id', 'SET NULL', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-products-main_photo_id}}', '{{%products}}');

        $this->dropColumn('{{%products}}', 'main_photo_id');
    }
}
