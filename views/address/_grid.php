<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \modernkernel\billing\models\Address */

?>
<div class="col-sm-4">
    <div class="box box-primary box-address">
        <div class="box-body">
            <div class="text-uppercase"><strong><?= $model->contact_name ?></strong></div>
            <div><?= $model->street_address_1 ?></div>
            <?php if(!empty($model->street_address_2)):?>
            <div><?= $model->street_address_2 ?></div>
            <?php endif;?>
            <div><?= $model->city ?>, <?= $model->state ?> <?= $model->zip_code ?></div>
            <div><?= \common\Core::getCountryText($model->country) ?></div>
            <div><?= $model->getAttributeLabel('phone') ?>: <?= $model->phone ?></div>
        </div>
        <div class="box-footer">
            <?= Html::a(Yii::$app->getModule('billing')->t('Update'), ['update', 'id'=>(string)$key], ['data-pjax'=>0]) ?> | <?= Html::a(Yii::$app->getModule('billing')->t('Delete'), ['delete', 'id'=>(string)$key], ['data'=>['pjax'=>0, 'method'=>'post', 'confirm'=>Yii::$app->getModule('billing')->t('Are you sure want to delete this address?')]]) ?>
        </div>
    </div>
</div>