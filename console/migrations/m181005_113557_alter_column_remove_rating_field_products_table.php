<?php

use yii\db\Migration;

/**
 * Class m181005_113557_alter_column_remove_rating_field_products_table
 */
class m181005_113557_alter_column_remove_rating_field_products_table extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%products}}','rating');
    }
    public function safeDown()
    {
        $this->addColumn('{{%products}}','rating',$this->float());
    }
}
