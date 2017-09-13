<?php

/* @var $this yii\web\View */
/* @var $model \modernkernel\billing\models\Invoice */

?>

<?= Yii::$app->getModule('billing')->t('Dear {NAME},', ['NAME' => $model->account->fullname]) ?>


<?= Yii::$app->getModule('billing')->t('Thank you for placing your order with us! Your total amount due is {TOTAL}. More details about your purchase are included below.', ['TOTAL'=>Yii::$app->formatter->asCurrency($model->total, $model->currency)]) ?>


<?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID' => $model->id_invoice]) ?>

<?php foreach ($model->items as $item):?>
<?= Yii::$app->getModule('billing')->t(' - {ITEM}: {PRICE}', ['ITEM'=>$item->name, 'PRICE'=>Yii::$app->formatter->asCurrency($item->price, $model->currency)]) ?>

<?php endforeach;?>

<?= Yii::$app->getModule('billing')->t('Shipping: {SHIP}', ['SHIP' => Yii::$app->formatter->asCurrency($model->shipping, $model->currency)]) ?>

<?= Yii::$app->getModule('billing')->t('Tax: {TAX}', ['TAX' => Yii::$app->formatter->asCurrency($model->tax, $model->currency)]) ?>

<?= Yii::$app->getModule('billing')->t('Total: {TOTAL}', ['TOTAL' => Yii::$app->formatter->asCurrency($model->total, $model->currency)]) ?>


<?= Yii::$app->getModule('billing')->t('Pay Now: {URL}', ['URL'=>$model->getInvoiceUrl(true)]) ?>
