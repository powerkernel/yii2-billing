<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use common\models\Setting;
use harrytang\hosting\models\Invoice;
use modernkernel\fontawesome\Icon;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $info [] */
/* @var $model modernkernel\billing\models\Invoice */

$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
$generator=new \Picqer\Barcode\BarcodeGeneratorSVG();
?>
<div class="invoice-view">
    <section class="invoice" style="margin: 0">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <img src="/images/logo-mini.svg" class="img-responsive"
                         style="max-height: 24px; vertical-align: bottom; display: inline-block"
                         alt="<?= Yii::$app->name ?>"/> <?= Yii::$app->name ?>
                    <span class="pull-right" style="max-height: 24px; vertical-align: bottom; display: block">
                        <?= $generator->getBarcode($model->id, $generator::TYPE_CODE_128, 1.5, 24) ?>
                    </span>
                </h2>

            </div>

            <!-- /.col -->
        </div>

        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <?= Yii::$app->getModule('billing')->t('From') ?>
                <address>
                    <div><strong><?= Setting::getValue('merchantName') ?></strong></div>
                    <div><?= Setting::getValue('merchantAddress') ?></div>
                    <div><?= Setting::getValue('merchantCity') ?><?= !empty($state = Setting::getValue('merchantState')) ? ', ' . $state : '' ?><?= !empty($zip = Setting::getValue('merchantZip')) ? ', ' . $zip : '' ?><?= !empty($country = Setting::getValue('merchantCountry')) ? ', ' . $country : '' ?></div>
                    <div><?= Yii::$app->getModule('billing')->t('Phone:') ?> <?= Setting::getValue('merchantPhone') ?></div>
                    <div><?= Yii::$app->getModule('billing')->t('Email:') ?> <?= Setting::getValue('merchantEmail') ?></div>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <?= Yii::$app->getModule('billing')->t('To') ?>
                <address>
                    <div><strong><?= $info['f_name'] ?> <?= $info['l_name'] ?></strong></div>
                    <?php if (!empty($info['address'])): ?>
                        <div><?= $info['address'] ?></div><?php endif; ?>
                    <?php if (!empty($info['address2'])): ?>
                        <div><?= $info['address2'] ?></div><?php endif; ?>
                    <?php if (!empty($info['city'])): ?>
                        <div><?= $info['city'] ?><?= !empty($info['state']) ? ', ' . $info['state'] : '' ?><?= !empty($info['zip']) ? ', ' . $info['zip'] : '' ?><?= !empty($info['country']) ? ', ' . $info['country'] : '' ?></div><?php endif; ?>
                    <div><?= Yii::$app->getModule('billing')->t('Phone:') ?> <?= $info['phone'] ?></div>
                    <div><?= Yii::$app->getModule('billing')->t('Email:') ?> <?= $info['email'] ?></div>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <div class="">
                    <b><?= Yii::$app->getModule('billing')->t('Invoice #:') ?></b> <?= $model->id ?></div>
                <div class="">
                    <b><?= Yii::$app->getModule('billing')->t('Date:') ?></b> <?= Yii::$app->formatter->asDate($model->created_at) ?></div>
                <div class="<?= empty($model->payment_method) ? 'hidden' : '' ?>">
                    <b><?= $model->getAttributeLabel('payment_method') ?>: </b> <?= $model->payment_method ?></div>
                <div class="<?= empty($model->payment_date) ? 'hidden' : '' ?>">
                    <b><?= $model->getAttributeLabel('payment_date') ?>
                        : </b> <?= Yii::$app->formatter->asDate($model->payment_date) ?></div>
                <div class="<?= empty($model->transaction) ? 'hidden' : '' ?>">
                    <b><?= $model->getAttributeLabel('transaction') ?>: </b> <?= $model->transaction ?></div>
                <div class="no-print">
                    <b><?= Yii::$app->getModule('billing')->t('Status:') ?></b> <?= $model->statusText ?></div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?= Html::encode('#') ?></th>
                        <th><?= Yii::$app->getModule('billing')->t('Product') ?></th>
                        <th><?= Yii::$app->getModule('billing')->t('Price') ?></th>
                        <th><?= Yii::$app->getModule('billing')->t('Subtotal') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($model->items as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $item->name ?> <span
                                        class="badge <?= $item->quantity == 1 ? 'hidden' : '' ?>"><?= $item->quantity ?></span>
                            </td>
                            <td><?= Yii::$app->formatter->asCurrency($item->price, $model->currency) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($item->quantity * $item->price, $model->currency) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- payment row -->
        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="lead" style="margin-bottom: 0">
                    <?= Yii::$app->getModule('billing')->t('Bank Transfer:') ?>
                </p>
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    <?= nl2br(Setting::getValue('merchantBank')) ?>
                </p>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th style="width:50%"><?= Yii::$app->getModule('billing')->t('Subtotal:') ?></th>
                            <td><?= Yii::$app->formatter->asCurrency($model->subtotal, $model->currency) ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::$app->getModule('billing')->t('Shipping:') ?></th>
                            <td><?= Yii::$app->formatter->asCurrency($model->shipping, $model->currency) ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::$app->getModule('billing')->t('Tax:') ?></th>
                            <td><?= Yii::$app->formatter->asCurrency($model->tax, $model->currency) ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::$app->getModule('billing')->t('Total:') ?></th>
                            <td><?= Yii::$app->formatter->asCurrency($model->total, $model->currency) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center no-print">
                    <?php if (in_array($model->status, [Invoice::STATUS_PENDING])): ?>
                        <div><?= Html::a(Yii::$app->getModule('billing')->t('Pay Now'), Yii::$app->urlManager->createUrl(['/billing/invoice/pay', 'id' => $model->id, 'method' => 'paypal']), ['class' => 'btn btn-success']) ?></div>
                        <div style="font-size: 2.5em" class="text-muted">
                            <?= Icon::widget(['icon' => 'cc-paypal']) ?>
                            <?= Icon::widget(['icon' => 'cc-visa']) ?>
                            <?= Icon::widget(['icon' => 'cc-mastercard']) ?>
                            <?= Icon::widget(['icon' => 'cc-amex']) ?>
                            <?= Icon::widget(['icon' => 'cc-discover']) ?>

                        </div>
                        <div>
                            <img class="img-responsive hidden"
                                 src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppppcmcvdam.png"
                                 alt="Pay with PayPal, PayPal Credit or any major credit card"/>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


        <?php if(Yii::$app->user->can('admin') && $model->status==Invoice::STATUS_PENDING):?>
            <!-- update row -->
            <div><hr /></div>
            <div class="row">
                <div class="col-sm-6">
                    <?= Html::beginForm(Yii::$app->urlManager->createUrl(['/billing/invoice/discount', 'id'=>$model->id]), 'post', ['class'=>'form-inline']) ?>
                    <div class="form-group">
                        <label class="sr-only" for="discountAmount"><?= Yii::t('billing', 'Amount') ?></label>
                        <div class="input-group">
                            <div class="input-group-addon"><?= $model->currency ?></div>
                            <input type="text" class="form-control" name="discountAmount" id="discountAmount" placeholder="<?= Yii::t('billing', 'Amount') ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= Yii::t('billing', 'Add discount') ?></button>
                    <?= Html::endForm() ?>
                </div>
                <div class="col-sm-6">
                    <?= Html::a(Yii::t('billing', 'Update'), ['update', 'id'=>$model->id], ['class' => 'btn btn-primary pull-right']) ?>
                </div>
            </div>
            <!-- /.row -->
        <?php endif;?>

    </section>
</div>