<?php

use yii\db\Migration;

/**
 * Class m180630_160851_add_nfc_tag_device
 */
class m180630_160851_add_nfc_tag_device extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('device','nfc_tag', 'varchar(50)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('device', 'nfc_tag');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180630_160851_add_nfc_tag_device cannot be reverted.\n";

        return false;
    }
    */
}
