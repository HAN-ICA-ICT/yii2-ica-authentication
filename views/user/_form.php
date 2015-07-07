<?php
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model icalab\auth\models\User */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\rbac\ManagerInterface;

?>

<?php
if(Yii::$app->session->hasFlash('userUpdated'))
{
?>
<div class="alert alert-success">
<?= Yii::t('ica_auth', 'The user details have been updated.'); ?>
</div>
<?php 
}
?>

<?php
$form = ActiveForm::begin([
    'id' => 'user-form',
    'layout' => 'horizontal',
]);
echo $form->errorSummary($model);
echo $form->field($model, 'email')->input('email');
echo $form->field($model, 'password')->input('password');
?>


<div class="form-group field-user-password_confirm">
    <label class="control-label col-sm-3" for="password_confirm"><?= Yii::t('ica_auth', 'Confirm password') ?></label>
    <div class="col-sm-6">
    <?php
        echo Html::passwordInput('password_confirm', null, ['class' => 'form-control']);
?>
    </div>
</div>

<div class="form-group field-auth_items">
<label class="control-label col-sm-3"><?= Yii::t('ica_auth', 'Roles'); ?></label>
<?php
$allAuthItems = [];
$enabledAuthItems = [];
foreach(Yii::$app->getAuthManager()->roles as $role)
{
    $allAuthItems[$role->name] = $role->name;
    if(strlen($role->description) > 0)
    {
        $allAuthItems[$role->name] .= '(' . $role->description . ')';
    }
    if(Yii::$app->getAuthManager()->checkAccess($model->getId(), $role->name))
    {
        $enabledAuthItems[] = $role->name;
    }
}
echo Html::checkboxList('auth_items', $enabledAuthItems, $allAuthItems, [
    'tag' => 'div class="col-sm-6"',
'itemOptions' => ['labelOptions' => ['class' => 'col-lg-6 col-sm-6 col-md-6 col-xs-12']]]);
?>
</div>
<?php
if($model->isNewRecord)
{
    echo Html::submitButton(Yii::t('ica_auth', 'Create'), ['class' => 'btn btn-primary']);
}
else
{
    echo Html::submitButton(Yii::t('ica_auth', 'Update'), ['class' => 'btn btn-primary']);
}

ActiveForm::end();

