<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Service */

$this->title = Yii::t('app/service', 'Update {modelClass}: ', [
    'modelClass' => 'Service',
]) . ' ' . $model->SERVICE_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/service', 'Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->SERVICE_ID, 'url' => ['view', 'id' => $model->SERVICE_ID]];
$this->params['breadcrumbs'][] = Yii::t('app/service', 'Update');
?>
<div class="service-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
