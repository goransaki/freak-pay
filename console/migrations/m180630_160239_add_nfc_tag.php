<?php

use yii\db\Migration;

/**
 * Class m180630_160239_add_nfc_tag
 */
class m180630_160239_add_nfc_tag extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('card','nfc_tag', 'varchar(50)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('card', 'nfc_tag');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180630_160239_add_nfc_tag cannot be reverted.\n";

        return false;
    }
    */
}
