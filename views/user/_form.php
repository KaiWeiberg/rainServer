<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => 45]) ?>
    
    <?php if($model->isNewRecord): ?>   
        <?= $form->field($model, 'password')->textInput(['maxlength' => 16]) ?>

        <?= $form->field($model, 'passwordRepeat')->textInput(['maxlength' => 16]) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'sms')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'language')
            ->dropDownList( ['de'=>Yii::t('app', 'German'),
                             'en'=>Yii::t('app', 'English')],
                            ['prompt'=>'Select...']);?>

    <?= $form->field($model, 'role')
            ->dropDownList( [User::cUSER =>Yii::t('app', 'User'),
                             User::cADMIN =>Yii::t('app', 'Administrator'),
                             User::cDEVELOPER =>Yii::t('app', 'Developer')],
                             ['prompt'=>'Select...']
                          );?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
