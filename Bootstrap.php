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
        /*
        //$app->controllerMap['authentication'] = 'icalab\auth\controllers\MediafileController';
        //$app->controllerMap['mediafiletype'] = 'icalab\auth\controllers\MediafiletypeController';
        Yii::$app->i18n->translations['ica_auth*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@icalab/auth/messages',
        ];
         */
    }
}
