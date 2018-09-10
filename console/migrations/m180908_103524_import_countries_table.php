<?php

use yii\db\Migration;

/**
 * Class m180908_103524_import_countries_table
 */
class m180908_103524_import_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(\Yii::getAlias('@app') . '/../countries.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('countries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180908_103524_import_countries_table cannot be reverted.\n";

        return false;
    }
    */
}
