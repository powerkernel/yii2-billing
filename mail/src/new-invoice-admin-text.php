<?php

/* @var $this yii\web\View */
/* @var $model \modernkernel\billing\models\Invoice */

?>

<?= Yii::$app->getModule('billing')->t('Greetings from {APP},', ['APP' => Yii::$app->name]) ?>


<?= Yii::$app->getModule('billing')->t('A new order has just been placed') ?>


<?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID' => $model->id_invoice]) ?>


<?= Yii::$app->getModule('billing')->t('View Invoice: {URL}', ['URL'=>$model->getAdminInvoiceUrl(true)]) ?>
