<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use modernkernel\billing\models\Invoice;
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
            <?= $form->field($model, 'shipping')->textInput(['maxlength' => true]) ?>
            <?php $info=json_decode($model->info, true); if(empty($info['tax_id'])):?>
            <?= $form->field($model, 'tax')->textInput(['maxlength' => true]) ?>
            <?php endif;?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'currency')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            <?= $form->field($model, 'transaction')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'payment_method')->dropDownList(Invoice::getPaymentMethodOption(), ['prompt'=>'']) ?>
            <?php
                //$form->field($model, 'payment_date')->hiddenInput()->label(false)
            ?>
            <?php
//            $form->field($model, 'payment_date_picker')->widget(DatePicker::classname(), [
//                'options' => ['class' => 'form-control'],
//                'clientOptions' => [
//                    'altField' => '#invoice-payment_date',
//                    'altFormat' => 'mm/dd/yy',
//                    'changeYear' => true,
//                    'changeMonth' => true,
//                    //'onSelect' => new \yii\web\JsExpression('function(){$("#invoice-payment_date").val($("#invoice-payment_date").val()/1000);}')
//                ],
//            ])
            ?>
            <?= $form->field($model, 'status')->dropDownList(Invoice::getStatusOption()) ?>
        </div>
    </div>
    <div class="form-group">
        <?= \common\components\SubmitButton::widget(['text'=>$model->isNewRecord ? Yii::t('billing', 'Create') : Yii::t('billing', 'Update'), 'options'=>['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
