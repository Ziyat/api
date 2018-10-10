<?php

use yii\db\Migration;

/**
 * Class m181009_113459_alter_table_ratings_add_name_field
 */
class m181009_113459_alter_table_ratings_add_name_field extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%ratings}}','name',$this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%ratings}}','name');
    }

}
