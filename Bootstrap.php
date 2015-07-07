<?php

namespace icalab\auth;
use \yii\base\BootstrapInterface;
use \Yii;
class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\web\Application $app */
    public function bootstrap($app)
    {
        if(is_a($app, 'yii\web\Application'))
        {
            $app->controllerMap['user'] = 'icalab\auth\controllers\UserController';
            $app->controllerMap['password-reset'] = 'icalab\auth\controllers\PasswordResetController';
        }
        // We're running from the console.
        else
        {
            $app->controllerMap['ica-init-auth'] = 'icalab\auth\commands\IcaInitAuthController';
        }


        Yii::$app->i18n->translations['ica_auth*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@icalab/auth/messages',
        ];
    }
}
