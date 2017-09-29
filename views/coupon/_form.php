<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use common\Core;
use conquer\select2\Select2Widget;
use modernkernel\billing\models\Coupon;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'currency')->widget(Select2Widget::className(), [
                'bootstrap' => false,
                'items' => Core::getCurrencyList(),
                'options' => ['prompt' => Yii::$app->getModule('billing')->t('Select Currency')]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'discount_type')->radioList(Coupon::getDiscountOption()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'begin_date_picker')->widget(DatePicker::classname(), [
                'options'=>['class'=>'form-control'],
                'clientOptions'=>[
                    'altField'=>'#coupon-begin_at',
                    'altFormat'=>'mm/dd/yy',
                    //'altFormat'=>'@',
                    'changeYear'=>true,
                    'changeMonth'=>true,
                    //'onSelect'=>new \yii\web\JsExpression('function(){$("#coupon-begin_at").val($("#coupon-begin_at").val()/1000);}')
                ],
            ]) ?>
            <?= $form->field($model, 'begin_at')->hiddenInput()->label(false)->error(['class'=>'hidden']) ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'end_date_picker')->widget(DatePicker::classname(), [
                'options'=>['class'=>'form-control'],
                'clientOptions'=>[
                    'altField'=>'#coupon-end_at',
                    'altFormat'=>'mm/dd/yy',
                    //'altFormat'=>'@',
                    'changeYear'=>true,
                    'changeMonth'=>true,
                    //'onSelect'=>new \yii\web\JsExpression('function(){$("#coupon-end_at").val($("#coupon-end_at").val()/1000);}')
                ],
            ]) ?>
            <?= $form->field($model, 'end_at')->hiddenInput()->label(false)->error(['class'=>'hidden']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'quantity')->textInput()->hint(Yii::$app->getModule('billing')->t('Enter <b>-1</b> for unlimited')) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'reuse')->radioList(\common\Core::getYesNoOption()) ?>
        </div>
    </div>
















    <?= $form->field($model, 'status')->dropDownList(Coupon::getStatusOption()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('billing', 'Create') : Yii::t('billing', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
