<?php

use yii\db\Migration;

/**
 * Class m180703_145711_add_role_column_users_table
 */
class m180703_145711_add_role_column_users_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'role', $this->string(64));
        $this->update('{{%users}}', ['role' => 'user']);
        $this->createIndex('idx_users_role', '{{%users}}', 'role');
    }

    public function down()
    {
        $this->dropColumn('{{%users}}','role');
    }
}
