<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Country;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */
?>
<div class="register-registerAsMember">

    <?php $form = ActiveForm::begin([
        'id' => 'registrationAsMember-form',
    ]); ?>
    
    <?= $form->field($model, 'USER_NICKNAME') ?>
    
    <?= $form->field($model, 'USER_LASTNAME') ?>
    
    <?= $form->field($model, 'USER_FORNAME') ?>
       
    <?= $form->field($model, 'USER_ADDRESS') ?>
    
    <?= $form->field($model, 'USER_COUNTRYID')->dropDownList(Country::getCountriesList(),[
        'prompt' => '- Choose a Country -',
        'onchange' => '$.get( "'.Url::toRoute('region/get-regions-list').'", { countryId: $(this).val() } )
            .done(function(data){
                $( "#'.Html::getInputId($model, 'USER_REGIONID').'" ).html( data );
            });
            '
    ]) ?>
    
    <?= $form->field($model, 'USER_REGIONID')->dropDownList([], [
        'prompt' => '- Choose a Country First -',
        'onchange' => '$.get( "'.Url::toRoute('city/get-cities-list').'", { countryId: $("#'.Html::getInputId($model, 'USER_COUNTRYID').'").val(), regionId:$(this).val() } )
            .done(function(data){
                $( "#'.Html::getInputId($model, 'USER_CITYID').'" ).html( data );
            });
            '
    ]) ?>
    
    <?= $form->field($model, 'USER_CITYID')->dropDownList([]) ?>
    
    <?= $form->field($model, 'USER_PHONE') ?>

    <?= $form->field($model, 'USER_MAIL') ?>

    <?= $form->field($model, 'USER_PASSWORD')->passwordInput() ?>
    
    
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app/user', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- register-registerAsMember -->
