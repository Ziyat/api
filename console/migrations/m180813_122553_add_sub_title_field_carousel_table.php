<?php

use yii\db\Migration;

/**
 * Class m180813_122553_add_sub_title_field_carousel_table
 */
class m180813_122553_add_sub_title_field_carousel_table extends Migration
{

    public function up()
    {
        $this->addColumn('{{%carousels}}','sub_title',$this->string()->null());
    }

    public function down()
    {
        $this->dropColumn('{{%carousels}}','sub_title');
    }

}
