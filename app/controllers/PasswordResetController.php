<?php

/**
 * Password reset controller. How it works:
 * Somewhere (in a mobile app, say) a process
 * is triggered that generates a new password reset request.
 * The resulting token is then passed along with the user's
 * email address to the "request" method. Here the
 * user can enter a new password.
 * The "reset" method makes sure the reset request is valid
 * and updates the password.
 *
 * If you do not want to provide your own method for generating request tokens,
 * you can use the provided generate method that generates a request token
 * and sends out an email message.
 */
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;
use app\models\ResetPassword;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;

class PasswordResetController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    /**
     * Generate a request token. Silently fails.
     *
     * Always generates empty output.
     */
    public function actionGenerate()
    {
        if(null === Yii::$app->request->post())
        {
            return '';
        }
        if(! isset(Yii::$app->request->post()['email']))
        {
            return '';
        }
        $user = User::findByEmail(Yii::$app->request->post()['email']);
        if(null === $user)
        {
            return '';
        }

        $request = new ResetPassword();
        $request->userid = $user->getId();
        $request->save();

        Yii::$app->mailer->compose('generate_password_reset_request_mail', 
            ['request' => $request])
            ->setFrom('todo@example.com')
            ->setTo($user->email)
            ->setSubject(Yii::t('ica_auth', 'Password reset request'))
            ->send();

        return '';



    }
    /**
     * Display the password reset form, if the token is ok.
     * @param token
     *      the token
     * @param email
     *      the email address
     */
    public function actionRequest($token, $email)
    {
        $user = User::findByEmail($email);
        if(null === $user)
        {
            throw new NotFoundHttpException();
        }

        $request = ResetPassword::findOne([
            'request_token' => $token, 
            'userid' => $user->getId(),
        ]);
        if(null === $request)
        {
            throw new NotFoundHttpException();
        }

        $request->generateResetToken();
        $request->save();

        return $this->render('request', ['request' => $request]);
    }

    /**
     * Reset the password for a user.
     */
    public function actionReset()
    {
        if(null === Yii::$app->request->post())
        {
            throw new BadRequestHttpException();
        }
        $post = Yii::$app->request->post();
        if(! isset($post['User'])
            || ! isset($post['ResetPassword'])
            || ! isset($post['User']['email'])
            || ! isset($post['ResetPassword']['reset_token'])
            || ! isset($post['password'])
            || ! isset($post['password_confirm']))
        {
            throw new BadRequestHttpException();
        }

        $user = User::findByEmail($post['User']['email']);
        if(null === $user)
        {
            throw new NotFoundHttpException();
        }

        $request = ResetPassword::findOne([
            'reset_token' => $post['ResetPassword']['reset_token'], 
            'userid' => $user->getId(),
        ]);
        if(null == $request)
        {
            throw new NotFoundHttpException();
        }

        if($post['password'] != $post['password_confirm'])
        {
            Yii::$app->getSession()->setFlash('error',
                Yii::t('ica_auth', 'Passwords do not match.'));
            $this->redirect(Yii::$app->request->getReferrer());
            return;
        }

        $user->setPassword($post['password']);
        if($user->save(true, array('password_hash')))
        {
            $request->delete();
            return $this->render('success');
        }

        Yii::$app->getSession()->setFlash('error',
            Yii::t('ica_auth', 'Unable to update password. Please try again or contact support..'));
        $this->redirect(Yii::$app->request->getReferrer());





    }


 

}

