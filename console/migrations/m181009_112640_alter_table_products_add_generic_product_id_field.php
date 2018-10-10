<?php

use yii\db\Migration;

/**
 * Class m181009_112640_alter_table_products_add_generic_product_id_field
 */
class m181009_112640_alter_table_products_add_generic_product_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'generic_product_id', $this->integer()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'generic_product_id');
    }


}
