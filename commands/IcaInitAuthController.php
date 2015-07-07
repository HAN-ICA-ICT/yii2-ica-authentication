<?php
/**
 * Command for initializing the ICA authentication database
 * after all the necessary migrations have been performed.
 */

namespace icalab\auth\commands;

use icalab\auth\models\User;
use yii\rbac\DbManager;
use yii\base\InvalidConfigException;
use yii\base\ErrorException;

/**
 * This command initializes the contents of the ICA authentication tables.
 */
class IcaInitAuthController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $authManager = \Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        if((new \yii\db\Query())
            ->select('*')
            ->from($authManager->itemTable)
            ->where('name=:name', [':name' => 'userManager'])
            ->count() > 0)
        {
            throw new InvalidConfigException('Illegal attempt to run setup: data already exists.');
        }

        if(null !== User::find()
            ->where(['email' => 'admin@admin.com'])
            ->one())
        {
            throw new InvalidConfigException('Illegal attempt to run setup: user admin@admin.com already exists.');
        }

        $admin = new User();
        $admin->setPassword('admin');
        $admin->email = 'admin@admin.com';
        $admin->status = User::STATUS_ACTIVE;

        if(! $admin->save())
        {
            throw new ErrorException('Unable to save default admin user: '
                . print_r($admin->getErrors(), true));
        }

        foreach(['userManager',
            'authItemEditor',
            'userAssignRoles',
            'authItemEditRule',
        ] as $roleName)
        {
            $role = $authManager->createRole($roleName);
            $authManager->add($role);
            $authManager->assign($role, $admin->getId());
        }







    }
}
