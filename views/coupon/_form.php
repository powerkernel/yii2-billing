<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
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

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->widget(Select2Widget::className(), [
        'bootstrap' => false,
        'items' => Core::getCurrencyList(),
        'options' => ['prompt' => Yii::$app->getModule('billing')->t('Select Currency')]
    ]) ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount_type')->radioList(Coupon::getDiscountOption()) ?>


    <?= $form->field($model, 'begin_date_picker')->widget(DatePicker::classname(), [
        'options'=>['class'=>'form-control'],
        'clientOptions'=>[
            'altField'=>'#coupon-begin_at',
            'altFormat'=>'@',
            'changeYear'=>true,
            'changeMonth'=>true,
            'onSelect'=>new \yii\web\JsExpression('function(){$("#coupon-begin_at").val($("#coupon-begin_at").val()/1000);}')
        ],
    ]) ?>
    <?= $form->field($model, 'begin_at')->hiddenInput()->label(false) ?>


    <?= $form->field($model, 'end_date_picker')->widget(DatePicker::classname(), [
        'options'=>['class'=>'form-control'],
        'clientOptions'=>[
            'altField'=>'#coupon-end_at',
            'altFormat'=>'@',
            'changeYear'=>true,
            'changeMonth'=>true,
            'onSelect'=>new \yii\web\JsExpression('function(){$("#coupon-end_at").val($("#coupon-end_at").val()/1000);}')
        ],
    ]) ?>
    <?= $form->field($model, 'end_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'quantity')->textInput()->hint(Yii::$app->getModule('billing')->t('Enter <b>-1</b> for unlimited')) ?>

    <?= $form->field($model, 'reuse')->radioList(\common\Core::getYesNoOption()) ?>

    <?= $form->field($model, 'status')->dropDownList(Coupon::getStatusOption()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('billing', 'Create') : Yii::t('billing', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
