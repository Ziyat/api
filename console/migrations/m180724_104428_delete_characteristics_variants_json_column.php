<?php

use yii\db\Migration;

/**
 * Class m180724_104428_delete_characteristics_variants_json_column
 */
class m180724_104428_delete_characteristics_variants_json_column extends Migration
{

    public function up()
    {
        $this->dropColumn('{{%characteristics}}','variants_json');
        $this->dropColumn('{{%characteristics}}','type');
        $this->dropColumn('{{%characteristics}}','required');
        $this->dropColumn('{{%characteristics}}','default');
        $this->dropColumn('{{%characteristics}}','sort');
    }

    public function down()
    {
        $this->addColumn('{{%characteristics}}','variants_json',$this->text());
        $this->dropColumn('{{%characteristics}}','type',$this->string(16));
        $this->dropColumn('{{%characteristics}}','required',$this->tinyInteger(1));
        $this->dropColumn('{{%characteristics}}','default',$this->string());
        $this->dropColumn('{{%characteristics}}','sort',$this->integer(11));

    }
}
