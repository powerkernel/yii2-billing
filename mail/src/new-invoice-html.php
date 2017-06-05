<?php

/* @var $this yii\web\View */
/* @var $model \modernkernel\billing\models\Invoice */

?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $model->getInvoiceUrl(true) ?>"/>
        <meta itemprop="name" content="<?= Yii::$app->getModule('billing')->t('View Invoice') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::$app->getModule('billing')->t('You\'ve received an invoice (#{ID}) from {APP}', ['ID'=>$model->id, 'APP'=>Yii::$app->name]) ?>"/>
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
                                        <?= Yii::$app->getModule('billing')->t('Greetings from {APP},', ['APP'=>Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= Yii::$app->getModule('billing')->t('We appreciate your business and thank you for choosing {APP}. Below is your invoice:', ['APP'=>Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block aligncenter">
                                        <table class="invoice">
                                            <tr>
                                                <td>
                                                    <?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID'=>$model->id]) ?><br>
                                                    <?= Yii::$app->formatter->asDate($model->created_at) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                        <?php foreach($model->items as $item):?>
                                                        <tr>
                                                            <td><?= $item->name ?></td>
                                                            <td class="alignright"><?= Yii::$app->formatter->asCurrency($item->price*$item->quantity, $model->currency) ?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                        <tr class="no-total">
                                                            <td class="alignright" width="80%">
                                                                <?= $model->getAttributeLabel('shipping') ?><br>
                                                                <?= $model->getAttributeLabel('tax') ?>
                                                            </td>
                                                            <td class="alignright">
                                                                <?= Yii::$app->formatter->asCurrency($model->shipping, $model->currency) ?><br>
                                                                <?= Yii::$app->formatter->asCurrency($model->tax, $model->currency) ?>
                                                            </td>
                                                        </tr>
                                                        <tr class="total">
                                                            <td class="alignright" width="80%">
                                                                <?= $model->getAttributeLabel('total') ?>
                                                            </td>
                                                            <td class="alignright">
                                                                <?= Yii::$app->formatter->asCurrency($model->total, $model->currency) ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block aligncenter" colspan="2">
                                                                <a href="<?= $model->getInvoiceUrl(true) ?>" class="btn-primary"><?= Yii::$app->getModule('billing')->t('View Invoice') ?></a>
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