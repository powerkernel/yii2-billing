<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

/* @var $model \modernkernel\billing\models\Address */

use common\Core;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

?>
<div class="widget-add-address">
    <?php
    Modal::begin([
        'id' => 'modal-add-address',
        'header' => '<h3>' . Yii::$app->getModule('billing')->t('New address') . '</h3>',
    ]);
    ?>

    <div class="address-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'country')->dropDownList(Core::getCountryList())->label(false) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'contact_name', ['inputOptions' => ['placeholder' => $model->getAttributeLabel('contact_name')]])->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'street_address_1', ['inputOptions' => ['placeholder' => $model->getAttributeLabel('street_address_1')]])->label(false) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'street_address_2', ['inputOptions' => ['placeholder' => $model->getAttributeLabel('street_address_2')]])->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'city', ['inputOptions' => ['placeholder' => $model->getAttributeLabel('city')]])->label(false) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'state', ['inputOptions' => ['placeholder' => $model->getAttributeLabel('state')]])->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'zip_code', ['inputOptions' => ['placeholder' => $model->getAttributeLabel('zip_code')]])->label(false) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'phone', ['inputOptions' => ['placeholder' => $model->getAttributeLabel('phone')]])->label(false) ?>
            </div>
        </div>


        <div class="form-group">
            <?= \common\components\SubmitButton::widget(['text'=>Yii::t('billing', 'Add'), 'options'=>['class' => 'btn btn-primary']]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php
    Modal::end();
    ?>
</div>