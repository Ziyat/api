<?php

use yii\db\Migration;

/**
 * Class m180806_133313_add_user_private_field
 */
class m180806_133313_add_user_private_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'private', $this->smallInteger()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'private');
    }
}