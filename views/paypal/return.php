<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

use modernkernel\fontawesome\Icon;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $invoice \modernkernel\billing\models\Invoice */

$this->title = Yii::$app->getModule('billing')->t('Complete Payment');
$keywords = '';
$description = '';

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex']);

/* Facebook */
//$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
//$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
//$this->registerMetaTag(['property' => 'og:type', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:image', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:url', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:app_id', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:admins', 'content' => '']);

/* Twitter */
//$this->registerMetaTag(['name'=>'twitter:title', 'content'=>$this->title]);
//$this->registerMetaTag(['name'=>'twitter:description', 'content'=>$description]);
//$this->registerMetaTag(['name'=>'twitter:card', 'content'=>'summary']);
//$this->registerMetaTag(['name'=>'twitter:site', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:image', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data2', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label2', 'content'=>'']);

/* breadcrumbs */
$this->params['breadcrumbs'][] = ['label' => 'label', 'url' => '#'];

/* layout */
// Yii::$app->controller->layout = '@vendor/harrytang/yii2-theme/views/layouts/page';
?>
<div class="billing-paypal-return">
    <div class="row">
        <div class="col-lg-6 col-lg-push-3 col-md-8 col-md-push-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1 class="box-title"><?= $this->title ?></h1>
                    <div class="box-tools pull-right">
                        <?= Html::a(Icon::widget(['icon'=>'times']), $invoice->getInvoiceUrl(), ['btn btn-box-tool']) ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="text-center text-success">
                        <p><?= Icon::widget(['icon' => 'check-circle-o']) ?>
                        <?= Yii::$app->getModule('billing')->t('Payment Authorized') ?></p>
                    </div>
                    <div class="text-center">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th>Invoice</th>
                                    <td><?= $invoice->id_invoice; ?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><?= Yii::$app->formatter->asCurrency($invoice->total, $invoice->currency); ?></td>
                                </tr>
                            </table>
                        </div>

                    </div>
                    <div class="text-center">
                        <?= Html::beginForm() ?>
                        <?= Html::submitButton($this->title,['name'=>'complete', 'class'=>'btn btn-success', 'value'=>'true'])?>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>