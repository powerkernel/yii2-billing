<?php

/* @var $this yii\web\View */
/* @var $model \modernkernel\billing\models\Invoice */

?>
<div itemscope="" itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope="" itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $model->getInvoiceUrl(true) ?>">
        <meta itemprop="name" content="<?= Yii::$app->getModule('billing')->t('View Invoice') ?>">
    </div>
    <meta itemprop="description" content="<?= Yii::$app->getModule('billing')->t('You\'ve received an invoice (#{ID}) from {APP}', ['ID'=>$model->id, 'APP'=>Yii::$app->name]) ?>">
</div>

<table class="body-wrap" style="background-color: #f6f6f6; width: 100%;" width="100%" bgcolor="#f6f6f6">
    <tr>
        <td style="vertical-align: top;" valign="top"></td>
        <td class="container" width="600" style="vertical-align: top; display: block !important; max-width: 600px !important; margin: 0 auto !important; clear: both !important;" valign="top">
            <div class="content" style="max-width: 600px; margin: 0 auto; display: block; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff; border: 1px solid #e9e9e9; border-radius: 3px;" bgcolor="#fff">
                    <tr>
                        <td class="content-wrap" style="vertical-align: top; padding: 20px;" valign="top">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;" valign="top">
                                        <?= Yii::$app->getModule('billing')->t('Greetings from {APP},', ['APP'=>Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;" valign="top">
                                        <?= Yii::$app->getModule('billing')->t('We appreciate your business and thank you for choosing {APP}. Below is your invoice:', ['APP'=>Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block aligncenter" style="vertical-align: top; padding: 0 0 20px; text-align: center;" valign="top" align="center">
                                        <table class="invoice" style="margin: 20px auto; text-align: left; width: 100%;" width="100%" align="left">
                                            <tr>
                                                <td style="vertical-align: top; padding: 5px 0;" valign="top">
                                                    <?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID'=>$model->id]) ?><br>
                                                    <?= Yii::$app->formatter->asDate($model->created_at) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top; padding: 5px 0;" valign="top">
                                                    <table class="invoice-items" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                        <?php foreach($model->items as $item):?>
                                                        <tr>
                                                            <td style="vertical-align: top; padding: 5px 0; border-top: #eee 1px solid;" valign="top"><?= $item->name ?></td>
                                                            <td class="alignright" style="vertical-align: top; text-align: right; padding: 5px 0; border-top: #eee 1px solid;" valign="top" align="right"><?= Yii::$app->formatter->asCurrency($item->price*$item->quantity, $model->currency) ?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                        <tr class="no-total">
                                                            <td class="alignright" width="80%" style="vertical-align: top; text-align: right; padding: 5px 0; border-top: #eee 1px solid;" valign="top" align="right">
                                                                <?= $model->getAttributeLabel('shipping') ?><br>
                                                                <?= $model->getAttributeLabel('tax') ?>
                                                            </td>
                                                            <td class="alignright" style="vertical-align: top; text-align: right; padding: 5px 0; border-top: #eee 1px solid;" valign="top" align="right">
                                                                <?= Yii::$app->formatter->asCurrency($model->shipping, $model->currency) ?><br>
                                                                <?= Yii::$app->formatter->asCurrency($model->tax, $model->currency) ?>
                                                            </td>
                                                        </tr>
                                                        <tr class="total">
                                                            <td class="alignright" width="80%" style="vertical-align: top; text-align: right; padding: 5px 0; border-top: 2px solid #333; border-bottom: 2px solid #333; font-weight: 700;" valign="top" align="right">
                                                                <?= $model->getAttributeLabel('total') ?>
                                                            </td>
                                                            <td class="alignright" style="vertical-align: top; text-align: right; padding: 5px 0; border-top: 2px solid #333; border-bottom: 2px solid #333; font-weight: 700;" valign="top" align="right">
                                                                <?= Yii::$app->formatter->asCurrency($model->total, $model->currency) ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block aligncenter" colspan="2" style="vertical-align: top; text-align: center; padding: 5px 0; border-top: #eee 1px solid;" valign="top" align="center">
                                                                <a href="<?= $model->getInvoiceUrl(true) ?>" class="btn-primary" style="font-weight: bold; color: #FFF; background-color: #348eda; border: solid #348eda; border-width: 10px 20px; line-height: 2em; text-decoration: none; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize;"><?= Yii::$app->getModule('billing')->t('View Invoice') ?></a>
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
        <td style="vertical-align: top;" valign="top"></td>
    </tr>
</table>
