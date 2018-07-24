<?php

use yii\db\Migration;

/**
 * Handles the creation of table `characteristic_assignments`.
 */
class m180724_084516_create_characteristic_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%characteristic_assignments}}', [
            'characteristic_id' => $this->integer(),
            'category_id' => $this->integer(),
            'variants_json' => $this->text(),
        ]);
        $this->addPrimaryKey('{{%pk-characteristic_assignments}}','{{%characteristic_assignments}}',['characteristic_id','category_id']);
        $this->createIndex('{{%idx-characteristic_assignments-characteristic_id}}', '{{%characteristic_assignments}}', 'characteristic_id');
        $this->createIndex('{{%idx-characteristic_assignments-category_id}}', '{{%characteristic_assignments}}', 'category_id');
        $this->addForeignKey('{{%fk-characteristic_assignments-category_id}}', '{{%characteristic_assignments}}', 'category_id', '{{%categories}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('{{%fk-characteristic_assignments-characteristic_id}}', '{{%characteristic_assignments}}', 'characteristic_id', '{{%characteristics}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%characteristic_assignments}}');
    }
}
