<?php

namespace icalab\auth\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use icalab\auth\models\User;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'update'],
                            'allow' => true,
                            'roles' => ['userManager'],
                        ]
                    ],
                ],
            ];
    }

    /**
     * List all users.
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->orderBy('LOWER(email)'),
                'pagination' =>  [
                    'pageSize' => 20,
                ]
            ]);
        return $this->render('@icalab/auth/views/user/index', ['dataProvider' => $dataProvider]);
    }

    /**
     * Create a new user.
     * If creation is successful, the browser will be redirected to the 
     * 'update' page.
     */
    public function actionCreate()
    {
        $model = new User();
        if(null !== (Yii::$app->request->post()))
        {
            if($this->saveUser($model))
            {
                Yii::$app->session->setFlash('userUpdated');
                $this->redirect(['update', 'id' => $model->getId()]);
                return;
            }

        }

        return $this->render('@icalab/auth/views/user/create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = User::findOne(['id' => $id]);
        if(null === $model)
        {
            throw new NotFoundHttpException(Yii::t('ica_auth', 'No such user.'));
        }

        if($this->saveUser($model))
        {
            Yii::$app->session->setFlash('userUpdated');
        }
        
        return $this->render('@icalab/auth/views/user/update', ['model' => $model]);
    }

    /**
     * Save a user to a model.
     * @param $model the model to save
     * @return whether saving was successful or not
     */
    private function saveUser($model)
    {
        if(null === $model)
        {
            throw new Exception('No model supplied.');
        }

        if(null === Yii::$app->request->post() || ! isset(Yii::$app->request->post()['User']))
        {
            return false;
        }

        $canSave = true;
        // First check if we want to change the password and, if so, if the
        // new password is ok.
        $newPassword = null;
        if(isset(Yii::$app->request->post()['User']['password']) && strlen(Yii::$app->request->post()['User']['password']))
        {
            $passwordConfirm = Yii::$app->request->post()['User']['password'];
            if(isset(Yii::$app->request->post()['password_confirm']))
            {
                $passwordConfirm = Yii::$app->request->post()['password_confirm'];
            }
            if($passwordConfirm != Yii::$app->request->post()['User']['password'])
            {
                $model->addError('password', Yii::t('ica_auth', 'Passwords don\'t match.'));
                $canSave = false;
            }
            // The new password is ok. Allow updating it.
            else
            {
                $newPassword = $passwordConfirm;
            }
        }

        if( ! $canSave )
        {
            return false;
        }

        if(null !== $newPassword)
        {
            $model->setPassword($newPassword);
            if(! $model->validate(['password_hash']))
            {
                return false;
            }
        }

        $model->email = Yii::$app->request->post()['User']['email'];
        if(isset(Yii::$app->request->post()['User']['is_active']))
        {
            $model->is_active = Yii::$app->request->post()['User']['is_active'];
        }
        $model->auth_key =  $model->getAuthKey();
        if(! $model->save(true, ['email', 'password_hash', 'is_active', 'auth_key', 'created_at', 'updated_at']))
        {
            return false;
        }

        if(isset(Yii::$app->request->post()['auth_items'])
            && is_array(Yii::$app->request->post()['auth_items']))
        {
            $allAssignmentsValid = true;
            foreach(Yii::$app->request->post()['auth_items'] as $roleName)
            {
                if(null === Yii::$app->getAuthManager()->getRole($roleName))
                {
                    $allAssignmentsValid = false;
                    break;
                }
            }

            if($allAssignmentsValid)
            {
                Yii::$app->getAuthManager()->revokeAll($model->getId());
                foreach(Yii::$app->request->post()['auth_items'] as $roleName)
                {
                    Yii::$app->getAuthManager()->assign(
                        Yii::$app->getAuthManager()->getRole($roleName),
                        $model->getId());
                }
            }
        }

        return true;
    }



}
