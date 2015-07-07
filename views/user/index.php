<?php
/* @var $this yii\web\View */
/* @var $dataProvider dataProvider */

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = Yii::t('ica_auth', 'Users');
?>

<div class="user-index">
<?php
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => function($model, $key, $index, $widget)
    {
        return 
            Html::a($model->email,
                Url::toRoute(['user/update', 'id' => $model->primaryKey]));
    }

    ]);
?>
    
</div>
