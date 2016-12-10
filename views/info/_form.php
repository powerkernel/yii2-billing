<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use common\Core;
use conquer\select2\Select2Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\BillingInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'f_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'l_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">


            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'country')->widget(Select2Widget::className(), [
                'bootstrap' => false,
                'items' => Core::getCountryList(),
                'options' => ['prompt' => Yii::$app->getModule('billing')->t('Select Country')]
            ]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('billing', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
