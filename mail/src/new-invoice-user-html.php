<?php

/* @var $this yii\web\View */
/* @var $model \powerkernel\billing\models\Invoice */

?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $model->getInvoiceUrl(true) ?>"/>
        <meta itemprop="name" content="<?= Yii::$app->getModule('billing')->t('Pay Invoice') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::$app->getModule('billing')->t('{APP}: you\'ve got a new invoice #{ID}', ['ID'=>$model->id_invoice, 'APP'=>Yii::$app->name]) ?>"/>
</div>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('billing')->t('Dear {NAME},', ['NAME'=>$model->account->fullname]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('billing')->t('Thank you for placing your order with us! Your total amount due is {TOTAL}. More details about your purchase are included below.', ['TOTAL'=>Yii::$app->formatter->asCurrency($model->total, $model->currency)]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block aligncenter">
                                        <table class="invoice">
                                            <tr>
                                                <td>
                                                    <?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID'=>$model->id_invoice]) ?><br>
                                                </td>
                                            </tr>
                                            <?php foreach ($model->items as $item):?>
                                            <tr>
                                                <td>
                                                    <?= Yii::$app->getModule('billing')->t(' - {ITEM}: {PRICE}', ['ITEM'=>$item->name, 'PRICE'=>Yii::$app->formatter->asCurrency($item->price, $model->currency)]) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                            <tr><td><?= Yii::$app->getModule('billing')->t('Shipping: {SHIP}', ['SHIP' => Yii::$app->formatter->asCurrency($model->shipping, $model->currency)]) ?></td></tr>
                                            <tr><td><?= Yii::$app->getModule('billing')->t('Tax: {TAX}', ['TAX' => Yii::$app->formatter->asCurrency($model->tax, $model->currency)]) ?></td></tr>
                                            <tr><td><?= Yii::$app->getModule('billing')->t('Total: {TOTAL}', ['TOTAL' => Yii::$app->formatter->asCurrency($model->total, $model->currency)]) ?></td></tr>

                                            <tr>
                                                <td>
                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td class="content-block aligncenter" colspan="2">
                                                                <a href="<?= $model->getInvoiceUrl(true) ?>" class="btn-primary"><?= Yii::$app->getModule('billing')->t('Pay Now') ?></a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>

            </div>

        </td>
        <td></td>
    </tr>
</table>
<link href="src/css/mailgun.css" media="all" rel="stylesheet" type="text/css" />