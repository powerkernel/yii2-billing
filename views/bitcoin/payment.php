<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $address string */

$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
$btc=0.0015;
?>
<div class="bitcoin-payment-view">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $this->title ?></h3>
        </div>
        <div class="box-body">
            <p><?= Yii::$app->getModule('billing')->t('To complete your payment, please send {BTC} BTC to the address below.', ['BTC'=>$btc]) ?></p>
            <?= $address ?>
        </div>
    </div>
</div>
