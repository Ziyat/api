<?php

use yii\db\Migration;

/**
 * Class m180710_143901_alter_table_insert_user
 */
class m180710_143901_alter_table_insert_user extends Migration
{


    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->insert('{{%users}}', [
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'password_hash' => \Yii::$app->security->generatePasswordHash('w5CacB7cxYk@'),
            'email' => 'termit_90@mail.ru',
            'phone' => '998974457018',
            'role' => 'administrator',
            'created_at' => time(),
            'updated_at' => time(),
        ],$tableOptions);

        $this->insert('{{%profiles}}', [
            'user_id' => 1
        ],$tableOptions);
    }


    public function safeDown()
    {
        $this->delete('{{%users}}','users.id = 1');
        $this->delete('{{%profiles}}','profiles.user_id = 1');

        return true;
    }

}
