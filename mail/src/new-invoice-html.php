<?php

/* @var $this yii\web\View */
/* @var $model \modernkernel\billing\models\Invoice */

?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $model->getAdminInvoiceUrl(true) ?>"/>
        <meta itemprop="name" content="<?= Yii::$app->getModule('billing')->t('View Invoice') ?>"/>
    </div>
    <meta itemprop="description" content="<?= Yii::$app->getModule('billing')->t('{APP}: New invoice #{ID} placed', ['ID'=>$model->id, 'APP'=>Yii::$app->name]) ?>"/>
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
                                        <?= Yii::$app->getModule('billing')->t('A new order has just been placed') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block aligncenter">
                                        <table class="invoice">
                                            <tr>
                                                <td>
                                                    <?= Yii::$app->getModule('billing')->t('Invoice #{ID}', ['ID'=>$model->id]) ?><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td class="content-block aligncenter" colspan="2">
                                                                <a href="<?= $model->getAdminInvoiceUrl(true) ?>" class="btn-primary"><?= Yii::$app->getModule('billing')->t('View Invoice') ?></a>
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