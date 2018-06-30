<?php

use yii\db\Migration;

/**
 * Class m180630_160232_add_firs_and_last_name_for_user
 */
class m180630_160232_add_firs_and_last_name_for_user extends Migration
{

    public function up()
    {
        $this->addColumn('user','last_name',$this->string(100)->after('id'));
        $this->addColumn('user','first_name',$this->string(100)->after('id'));
    }

    public function down()
    {
        $this->dropColumn('user','first_name');
        $this->dropColumn('user','first_name');
    }
}
