<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use common\Core;
use conquer\select2\Select2Widget;
use powerkernel\billing\models\Bank;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model powerkernel\billing\models\Bank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country')->widget(Select2Widget::className(), [
        'bootstrap' => false,
        'items' => Core::getCountryList(),
        'options' => ['prompt' => Yii::$app->getModule('billing')->t('Select Country')]
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'currency')->widget(Select2Widget::className(), [
        'bootstrap' => false,
        'items' => Core::getCurrencyList(),
        'options' => ['prompt' => Yii::$app->getModule('billing')->t('Select Country')]
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(Bank::getStatusOption()) ?>

    <div class="form-group">
        <?= \common\components\SubmitButton::widget(['text'=>$model->isNewRecord ? Yii::t('billing', 'Create') : Yii::t('billing', 'Update'), 'options'=>['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
