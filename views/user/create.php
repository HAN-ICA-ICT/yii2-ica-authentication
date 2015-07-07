<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model icalab\auth\models\User */

$this->title = Yii::t('ica_auth', 'Create new user.');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('ica_auth', 'Users'),
        'url' => Url::toRoute(['user/index']),
        ];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-update">
    <?php echo $this->render('_form', ['model' => $model]); ?>
</div>
