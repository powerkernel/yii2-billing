<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use common\Core;
use conquer\select2\Select2Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\Address */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country')->widget(Select2Widget::className(), [
        'bootstrap' => false,
        'items' => Core::getCountryList(),
        'options' => ['prompt' => Yii::$app->getModule('billing')->t('Select Country')]
    ]) ?>

    <?= $form->field($model, 'contact_name') ?>

    <?= $form->field($model, 'street_address_1') ?>

    <?= $form->field($model, 'street_address_2') ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'state') ?>

    <?= $form->field($model, 'zip_code') ?>

    <?= $form->field($model, 'phone') ?>

    <div class="form-group">
        <?= \common\components\SubmitButton::widget(['text'=>$model->isNewRecord ? Yii::t('billing', 'Create') : Yii::t('billing', 'Update'), 'options'=>['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
