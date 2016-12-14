<?php

/* @var $this yii\web\View */
/* @var $model \modernkernel\billing\models\Invoice */

?>

<?= Yii::$app->getModule('billing')->t('Greetings from {APP},', ['APP' => Yii::$app->name]) ?>

<?= Yii::$app->getModule('billing')->t('We appreciate your business and thank you for choosing {APP}. Below is your invoice:', ['APP' => Yii::$app->name]) ?>


<?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID' => $model->id]) ?>

<?= Yii::$app->formatter->asDate($model->created_at) ?>

<?php foreach ($model->items as $item): ?>
<?= $item->name ?>: <?= Yii::$app->formatter->asCurrency($item->price * $item->quantity, $model->currency) ?>
<?php echo "
"?>
<?php endforeach; ?>

<?= $model->getAttributeLabel('shipping') ?>: <?= Yii::$app->formatter->asCurrency($model->shipping, $model->currency) ?>

<?= $model->getAttributeLabel('tax') ?>: <?= Yii::$app->formatter->asCurrency($model->tax, $model->currency) ?>

<?= $model->getAttributeLabel('total') ?>: <?= Yii::$app->formatter->asCurrency($model->total, $model->currency) ?>


<?= Yii::$app->getModule('billing')->t('View Invoice: {URL}', ['URL'=>$model->getInvoiceUrl(true)]) ?>
