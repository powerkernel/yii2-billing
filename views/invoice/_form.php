<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use modernkernel\billing\models\Invoice;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'id')->textInput(['disabled' => true]) ?>
            <?= $form->field($model, 'subtotal')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            <?= $form->field($model, 'total')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'tax')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'currency')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            <?= $form->field($model, 'transaction')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'payment_method')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'payment_date')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'payment_date_picker')->widget(DatePicker::classname(), [
                'options' => ['class' => 'form-control'],
                'clientOptions' => [
                    'altField' => '#invoice-payment_date',
                    'altFormat' => '@',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'onSelect' => new \yii\web\JsExpression('function(){$("#invoice-payment_date").val($("#invoice-payment_date").val()/1000);}')
                ],
            ]) ?>
            <?= $form->field($model, 'status')->dropDownList(Invoice::getStatusOption()) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('billing', 'Create') : Yii::t('billing', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
