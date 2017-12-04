<?php

/* @var $this yii\web\View */
/* @var $model \powerkernel\billing\models\Invoice */

?>

<?= Yii::$app->getModule('billing')->t('Hi {USER},', ['USER' => $model->account->fullname]) ?>

<?= Yii::$app->getModule('billing')->t('We\'re sorry to let you know that your invoice has been canceled. You don\'t need to do anything else.') ?>

<?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID' => $model->id_invoice]) ?>

<?= Yii::$app->getModule('billing')->t('View Invoice: {URL}', ['URL'=>$model->getInvoiceUrl(true)]) ?>
