<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $this->execute($this->loadSql());
    }

    public function loadSql()
    {
        return file_get_contents( Yii::getAlias('@console'). "/migrations/schema.sql");
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
