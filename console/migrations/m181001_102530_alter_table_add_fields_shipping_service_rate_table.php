<?php

use yii\db\Migration;

/**
 * Class m181001_102530_alter_table_add_fields_shipping_service_rate_table
 */
class m181001_102530_alter_table_add_fields_shipping_service_rate_table extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%shipping_service_rates}}','width',$this->float()->null());
        $this->addColumn('{{%shipping_service_rates}}','height',$this->float()->null());
        $this->addColumn('{{%shipping_service_rates}}','length',$this->float()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%shipping_service_rates}}','width');
        $this->dropColumn('{{%shipping_service_rates}}','height');
        $this->dropColumn('{{%shipping_service_rates}}','length');
    }

}
