<?php

use yii\db\Migration;

/**
 * Class m180714_154802_add_product_quantity_field
 */
class m180714_154802_add_product_quantity_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%products}}', 'quantity', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%products}}', 'quantity');
    }
}
