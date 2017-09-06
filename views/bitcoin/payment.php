<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
use common\models\Setting;
use modernkernel\fontawesome\Icon;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
\common\plugins\moment\MomentAsset::register($this);

/* @var $this yii\web\View */
/* @var $invoice \modernkernel\billing\models\Invoice */
/* @var $bitcoin [] */

$this->params['breadcrumbs'][] = $this->title;

/* misc */
$js = file_get_contents(__DIR__ . '/payment.min.js');
$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="bitcoin-payment-view">
    <div class="row">
        <div class="col-lg-6 col-lg-push-3 col-md-8 col-md-push-2">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools pull-right">
                            <?= Html::a(Icon::widget(['icon'=>'times']), $invoice->getInvoiceUrl(), ['btn btn-box-tool']) ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-7">
                            <?= Yii::$app->getModule('billing')->t('Pay for invoice #{INVOICE}', ['INVOICE' => $invoice->id_invoice]) ?>
                        </div>
                        <div class="col-xs-5">
                            <div class="text-right"><?= Yii::$app->formatter->asDecimal($invoice->total) ?> <?= $invoice->currency ?></div>
                            <div class="text-right text-sm"><?= $bitcoin['amount'] ?> BTC</div>
                        </div>
                    </div>

                    <div id="payment-waiting" class="text-center">
                        <?= Icon::widget(['icon'=>'refresh fa-spin']) ?>
                        <?= Yii::$app->getModule('billing')->t('Waiting payment...') ?>
                        <div id="count-down" class="text-center text-bold" style="font-size: 1.2em"></div>
                    </div>

                    <div id="payment-received" class="hidden">
                        <div class="alert alert-success">
                            <?= Icon::widget(['icon'=>'check']) ?>
                            <?= Yii::$app->getModule('billing')->t('Payment received!') ?>
                        </div>
                        <div class="text-center">
                            <?= Html::a(Yii::$app->getModule('billing')->t('Return'), $invoice->getInvoiceUrl(), ['title' => Yii::$app->getModule('billing')->t('Return'), 'class' => 'btn btn-lg btn-primary']) ?>
                        </div>
                    </div>



                    <div id="btc-info" data-timeout="<?= Setting::getValue('btcPaymentTime') ?>">
                        <div style="margin-top: 10px;">
                        <?=
                        Tabs::widget([
                            'options' => ['class' => 'nav nav-tabs nav-justified'],
                            'items' => [
                                [

                                    'active' => true,
                                    'label' => Yii::$app->getModule('billing')->t('Scan'),
                                    'content' => $this->render('_scan', ['bitcoin' => $bitcoin])

                                ],
                                [

                                    'label' => Yii::$app->getModule('billing')->t('Copy'),
                                    'content' => $this->render('_copy', ['bitcoin' => $bitcoin])
                                ],

                            ],
                        ]);
                        ?>
                        </div>

                        <div class="text-center" style="margin-top: 20px; margin-bottom: 10px;">
                            <?= Html::a(Yii::$app->getModule('billing')->t('Open in Wallet').' '. Icon::widget(['icon'=>'external-link']), $bitcoin['url'], ['title' => Yii::$app->getModule('billing')->t('Open in Wallet'), 'class' => 'btn btn-lg btn-primary']) ?>
                        </div>
                    </div>

                    <div id="btc-expired" class="alert alert-danger hidden">
                        <?= Yii::$app->getModule('billing')->t('Payment expired!') ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<span id="check-payment-url" class="hidden" data-check-payment-url="<?= Yii::$app->urlManager->createUrl(['/billing/bitcoin/check-payment', 'address'=>$bitcoin['address']]) ?>"></span>
