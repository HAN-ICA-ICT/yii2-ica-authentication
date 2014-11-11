<?php
/* @var $form yii\bootstrap\ActiveForm */
/* @var $request app\models\ResetPassword */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
if(Yii::$app->session->hasFlash('error'))
{
?>
<div class="alert alert-danger" role="alert">
<?= Yii::$app->session->getFlash('error') ?>
</div>
<?php
}
?>

<?php 
$form = ActiveForm::begin([
    'id'=>'password-reset-form',
    'layout' => 'horizontal',
    'action' => Url::toRoute(['reset']),
]);
?>

<?= Html::activeHiddenInput($request->user, 'email') ?>
<?= Html::activeHiddenInput($request, 'reset_token') ?>

<div class="form-group row">
    <div class="col-sm-9">
<?= Yii::t('ica_auth', 'Reset password for account with email address "{email}".',
    ['email' => $request->user->email]);
?>
    </div>
</div>


    <?php echo $form->errorSummary($request->user);?>

<div class="form-group field-user-password">
    <label class="control-label col-sm-3" for="password"><?= Yii::t('ica_auth', 'Password') ?></label>
    <div class="col-sm-6">
        <?php
    echo Html::passwordInput('password', null, ['class' => 'form-control']);
    ?>
    </div>
</div>
<div class="form-group field-user-password_confirm">
    <label class="control-label col-sm-3" for="password_confirm"><?= Yii::t('ica_auth', 'Confirm password') ?></label>
    <div class="col-sm-6">
    <?php
        echo Html::passwordInput('password_confirm', null, ['class' => 'form-control']);
?>
    </div>
</div>


<?php
echo Html::submitButton(Yii::t('ica_auth', 'Update password'), ['class' => 'btn btn-primary']);

ActiveForm::end();



