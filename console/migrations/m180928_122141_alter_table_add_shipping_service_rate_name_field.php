<?php

use yii\db\Migration;

/**
 * Class m180928_122141_alter_table_add_shipping_service_rate_name_field
 */
class m180928_122141_alter_table_add_shipping_service_rate_name_field extends Migration
{
    public function safeUp()
    {
       $this->addColumn('{{%shipping_service_rates}}','name',$this->string()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%shipping_service_rates}}','name');
    }

}
