<?php

use yii\db\Migration;

/**
 * Class m180821_094858_alter_table_product_add_condition_field
 */
class m180821_094858_alter_table_product_add_condition_field extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%products}}','condition',$this->string()->defaultValue('brand new'));
    }


    public function safeDown()
    {
        $this->dropColumn('{{%products}}','condition');
    }
}
