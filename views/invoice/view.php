<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use powerkernel\billing\models\Setting;
use powerkernel\billing\components\CurrencyLayer;
use powerkernel\billing\models\Invoice;
use powerkernel\fontawesome\Icon;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $info [] */
/* @var $model powerkernel\billing\models\Invoice */
/* @var $coupon powerkernel\billing\models\CouponForm */

$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
$generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
?>
<div class="invoice-view">
    <section class="invoice" style="margin: 0">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <img src="/images/logo-mini.svg" class="img-responsive"
                         style="height: 30px; width: 30px; vertical-align: text-bottom; display: inline-block"
                         alt="<?= Yii::$app->name ?>"/> <?= Yii::$app->name ?>
                    <span class="pull-right visible-print"
                          style="max-height: 24px; vertical-align: bottom; display: block">
                        <?= $generator->getBarcode($model->id_invoice, $generator::TYPE_CODE_128, 1.5, 24) ?>
                    </span>
                </h2>

            </div>

            <!-- /.col -->
        </div>

        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <em><?= Yii::$app->getModule('billing')->t('From') ?></em>
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
                <em><?= Yii::$app->getModule('billing')->t('To') ?></em>
                <address>
                    <?php if (!empty($info['company'])): ?>
                        <div><strong><?= $info['company'] ?></strong></div>
                    <?php else: ?>
                        <div><strong><?= $info['f_name'] ?> <?= $info['l_name'] ?></strong></div>
                    <?php endif; ?>

                    <?php if (!empty($info['address'])): ?>
                        <div><?= $info['address'] ?></div><?php endif; ?>
                    <?php if (!empty($info['address2'])): ?>
                        <div><?= $info['address2'] ?></div><?php endif; ?>
                    <?php if (!empty($info['city'])): ?>
                        <div><?= $info['city'] ?><?= !empty($info['state']) ? ', ' . $info['state'] : '' ?><?= !empty($info['zip']) ? ', ' . $info['zip'] : '' ?><?= !empty($info['country']) ? ', ' . $info['country'] : '' ?></div><?php endif; ?>
                    <?php if (!empty($info['phone'])): ?>
                        <div><?= Yii::$app->getModule('billing')->t('Phone:') ?> <?= $info['phone'] ?></div>
                    <?php endif; ?>
                    <div><?= Yii::$app->getModule('billing')->t('Email:') ?> <?= $info['email'] ?></div>
                    <?php if (!empty($info['tax_id'])): ?>
                        <div><?= Yii::$app->getModule('billing')->t('Tax:') ?> <?= $info['tax_id'] ?></div>
                    <?php endif; ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <div class="">
                    <b><?= Yii::$app->getModule('billing')->t('Invoice #:') ?></b> <?= $model->id_invoice ?></div>
                <div class="">
                    <b><?= Yii::$app->getModule('billing')->t('Date:') ?></b> <?= Yii::$app->formatter->asDate($model->createdAt) ?>
                </div>

                <?php if (!empty($model->payment_method)): ?>
                    <div class="">
                        <b><?= $model->getAttributeLabel('payment_method') ?>: </b> <?= $model->payment_method ?></div>
                <?php endif; ?>

                <?php if (!empty($model->payment_date)): ?>
                    <div class="">
                        <b><?= $model->getAttributeLabel('payment_date') ?>
                            : </b> <?= Yii::$app->formatter->asDate($model->paymentDate) ?></div>
                <?php endif; ?>

                <?php if (!empty($model->transaction)): ?>
                    <div class="">
                        <b><?= $model->getAttributeLabel('transaction') ?>: </b> <?= $model->transaction ?></div>
                <?php endif; ?>
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
            <div class="col-sm-6 col-sm-push-6">
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
                            <td>
                                <?= Yii::$app->formatter->asCurrency($model->total, $model->currency) ?>
                                <?php if ($model->currency != 'USD' && $model->status == Invoice::STATUS_PENDING) : ?>
                                    <br/>
                                    <strong>(<?= Yii::$app->formatter->asCurrency((new CurrencyLayer())->convertToUSD($model->currency, $model->total), 'USD') ?>
                                        )</strong>
                                <?php endif; ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="no-print">
                    <?php if (in_array($model->status, [Invoice::STATUS_PENDING])): ?>

                        <?php if (!empty($coupon)): ?>
                            <?php $form = ActiveForm::begin(['layout' => 'default']); ?>
                            <div class="row">
                                <div class="col-sm-9">
                                    <?= $form->field($coupon, 'coupon')->textInput(['maxlength' => true, 'placeholder' => $coupon->getAttributeLabel('coupon')])->label(false) ?>
                                </div>
                                <div class="col-sm-3">
                                    <?= \common\components\SubmitButton::widget(['text' => Yii::t('billing', 'Apply'), 'options' => ['class' => 'btn btn-primary']]) ?>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <div>
                                <hr/>
                            </div>
                        <?php endif; ?>


                        <div class="text-center">
                            <?php if ($model->currency != 'USD'): ?>
                                <?= Html::a(
                                    Icon::widget(['icon' => 'paypal']) . ' ' . Yii::$app->getModule('billing')->t('Pay'),
                                    Yii::$app->urlManager->createUrl(['billing/invoice/pay', 'id' => (string)$model->id, 'method' => 'paypal']),
                                    [
                                        'class' => 'btn btn-primary',
                                        'data-confirm' => Yii::$app->getModule('billing')->t('Your invoice will be converted to USD currency.')
                                    ])
                                ?>
                            <?php else: ?>
                                <?= Html::a(Icon::widget(['icon' => 'paypal']) . ' ' . Yii::$app->getModule('billing')->t('Pay'), Yii::$app->urlManager->createUrl(['billing/invoice/pay', 'id' => (string)$model->id, 'method' => 'paypal']), ['class' => 'btn btn-primary']) ?>
                            <?php endif; ?>

                            <?= Html::a(Icon::widget(['icon' => 'btc']) . ' ' . Yii::$app->getModule('billing')->t('Pay with Bitcoin'), Yii::$app->urlManager->createUrl(['billing/invoice/pay', 'id' => (string)$model->id, 'method' => 'bitcoin']), ['class' => 'btn btn-warning']) ?>
                        </div>


                        <?php if ($model->currency != 'USD'): ?>
                            <div class="text-center text-muted"><?= Yii::$app->getModule('billing')->t('We will convert total amount into US Dollars (USD).') ?></div>
                        <?php endif; ?>


                        <div style="font-size: 2.5em" class="text-muted text-center">
                            <?= Icon::widget(['icon' => 'cc-paypal']) ?>
                            <?= Icon::widget(['icon' => 'cc-visa']) ?>
                            <?= Icon::widget(['icon' => 'cc-mastercard']) ?>
                            <?= Icon::widget(['icon' => 'cc-amex']) ?>
                            <?= Icon::widget(['icon' => 'cc-discover']) ?>
                            <?= Icon::widget(['icon' => 'btc']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6 col-sm-pull-6">
                <?php if ($model->status == Invoice::STATUS_PENDING): ?>
                    <?php foreach ($banks = $model->getBankInfo() as $i => $bank): ?>
                        <?php if ($i == 0): ?>
                            <p class="lead" style="margin-bottom: 0">
                                <?= Yii::$app->getModule('billing')->t('Bank Transfer:') ?>
                            </p>
                        <?php endif; ?>
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            <?= nl2br($bank['info']) ?><br/>
                            <strong><?= Yii::$app->getModule('billing')->t('Amount: {AMOUNT}', ['AMOUNT' => Yii::$app->formatter->asCurrency($bank['total'], $bank['currency'])]) ?></strong><br/>
                            <em><?= Yii::$app->getModule('billing')->t('(Please transfer the amount in {CURRENCY} as shown above)', ['CURRENCY' => $bank['currency']]) ?></em>
                        </p>
                    <?php endforeach; ?>
                    <?php if ($banks): ?>
                        <div class="well well-sm">
                            <strong class="text-danger"><?= Yii::$app->getModule('billing')->t('Important:') ?></strong>
                            <?= Yii::$app->getModule('billing')->t('Please enter {IID} in your Detail of Payment and send all amount in a single transaction.', ['IID' => '<strong>' . $model->id_invoice . '</strong>']) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        </div>


        <?php if (Yii::$app->id == 'app-backend' && Yii::$app->user->can('admin') && $model->status == Invoice::STATUS_PENDING): ?>
            <!-- update row -->
            <div>
                <hr/>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['/billing/invoice/discount', 'id' => (string)$model->id]), 'options' => ['class' => 'form-inline']]); ?>
                    <div class="form-group">
                        <label class="sr-only" for="discountAmount"><?= Yii::t('billing', 'Amount') ?></label>
                        <div class="input-group">
                            <div class="input-group-addon"><?= $model->currency ?></div>
                            <input type="text" class="form-control" name="discountAmount" id="discountAmount"
                                   placeholder="<?= Yii::t('billing', 'Amount') ?>">
                        </div>
                    </div>
                    <?= \common\components\SubmitButton::widget(['text' => Yii::t('billing', 'Add discount'), 'options' => ['class' => 'btn btn-primary', 'data-confirm' => Yii::t('billing', 'Are you sure you want to perform this action?')]]) ?>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-sm-6 text-right">
                    <?=
                    ButtonDropdown::widget([
                        'label' => Yii::$app->getModule('billing')->t('Update'),
                        'options' => ['class' => 'btn btn-warning'],
                        'dropdown' => [
                            'items' => [
                                [
                                    'label' => Yii::$app->getModule('billing')->t('Cancel'),
                                    'url' => Yii::$app->urlManager->createUrl(['/billing/invoice/cancel', 'id' => (string)$model->id]),
                                    'linkOptions' => ['data-confirm' => Yii::$app->getModule('billing')->t('Are you sure want to cancel this invoice?')]
                                ],
                                [
                                    'label' => Yii::$app->getModule('billing')->t('Edit'),
                                    'url' => Yii::$app->urlManager->createUrl(['/billing/invoice/update', 'id' => (string)$model->id]),
                                ]
                            ],
                            'options' => ['class' => 'dropdown-menu dropdown-menu-right']
                        ],
                    ]);
                    ?>
                    <?php Html::a(Yii::t('billing', 'Update'), ['update', 'id' => (string)$model->id], ['class' => 'btn btn-primary pull-right']) ?>
                </div>
            </div>
            <!-- /.row -->
        <?php endif; ?>
    </section>
</div>