<?php

/**
 * Migration for the ICA Authentication mechanism. Based on the
 * default YII authentication mechanism in the yii advanced application.
 *
 * Make sure you have installed the YII DB RBac before you perform this
 * migration!
 */
use yii\db\Schema;
use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\rbac\DbManager;

class m141104_000000_ica_authentication_init extends Migration
{
    public function up()
    {

        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
 
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'CONSTRAINT uc_user_email UNIQUE(email)',
        ], $tableOptions);

        $this->createTable('{{%resetpassword}}', [
            'id' => Schema::TYPE_PK,
            'userid' => Schema::TYPE_INTEGER . ' NOT NULL',
            'request_token' => Schema::TYPE_STRING . ' NOT NULL',
            'reset_token' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_resetpassword_userid',
            '{{%resetpassword}}', 'userid',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE');


    }

    public function down()
    {
        $this->dropTable('{{%resetpassword}}');
        $this->dropTable('{{%user}}');
    }
}
