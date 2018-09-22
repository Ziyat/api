<?php

use yii\db\Migration;

/**
 * Class m180922_111354_alter_table_add_weight_field
 */
class m180922_111354_alter_table_add_weight_field extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%shipping_service_rates}}', 'weight', $this->float()->null());
    }
    public function safeDown()
    {
        $this->dropColumn('{{%shipping_service_rates}}','weight');
    }
}
