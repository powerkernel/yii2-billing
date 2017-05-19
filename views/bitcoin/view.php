<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\BitcoinAddress */

$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Bitcoin Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="bitcoin-address-view">
    <div class="box box-info">
        <div class="box-body">
            <div class="table-responsive">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'address',
            'id_invoice',
            'id_account',
            'request_balance',
            'total_received',
            'final_balance',
            'tx_id',
            'tx_date',
            'tx_confirmed',
            'tx_check_date:dateTime',
            ['attribute' => 'status', 'value' =>$model->statusColorText, 'format'=>'raw'],
            //'created_at',
            'updated_at:dateTime',
        ],
    ]) ?>
            </div>
        </div>
    </div>
</div>
