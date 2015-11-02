<?php
/**
 * This file is part of Levya Identity.
 * 
 * Levya Identity is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Foobar. See file LICENSE(.md) in this source tree, 
 * if not, see <http://www.gnu.org/licenses/>.
 * 
 * Copyright (C) Levya Team Members
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Position */

$this->title = Yii::t('app/position', 'Update {modelClass}: ', [
    'modelClass' => 'Position',
]) . ' ' . $model->POSITION_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/position', 'Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->POSITION_ID, 'url' => ['view', 'id' => $model->POSITION_ID]];
$this->params['breadcrumbs'][] = Yii::t('app/position', 'Update');
?>
<div class="position-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
