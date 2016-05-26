<?php

use yii\db\Migration;
use yii\db\Schema;

class m160526_093732_activate_deactivate_users extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'is_active', Schema::TYPE_BOOLEAN);
        // We don't want to lock out all previous users, so activate them by 
        // default.
        Yii::$app->db->createCommand('UPDATE {{%user}} SET is_active = true')->execute();


    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'is_active');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
