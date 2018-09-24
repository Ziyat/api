<?php

use yii\db\Migration;

/**
 * Class m180924_113212_alter_table_add_id_field_shipping_assignments_table
 */
class m180924_113212_alter_table_add_id_field_shipping_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropPrimaryKey('{{%pk-shipping_assignments}}','{{%shipping_assignments}}');
        $this->addColumn('{{%shipping_assignments}}','id',$this->primaryKey());
        $this->alterColumn('{{%shipping_assignments}}','rate_id',$this->integer()->null());
        $this->renameTable('{{%shipping_assignments}}','{{%product_shipping}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shipping_assignments}}','id');
        $this->addPrimaryKey('{{%pk-shipping_assignments}}','{{%shipping_assignments}}',['product_id','rate_id']);
        $this->renameTable('{{%product_shipping}}','{{%shipping_assignments}}');
    }
}
