<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model powerkernel\billing\models\BitcoinAddress */

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
                        ['attribute' => 'address', 'value' => Html::a($model->address, 'https://blockchain.info/address/' . $model->address, ['target' => '_blank']), 'format' => 'raw'],
                        ['attribute' => 'id_invoice', 'value' => empty($model->invoice) ? null : Html::a($model->id_invoice, Yii::$app->urlManager->createUrl(['billing/invoice/view', 'id' => (string)$model->invoice->id]), ['target' => '_blank']), 'format' => 'raw'],
                        'id_account',
                        'request_balance',
                        'total_received',
                        'final_balance',
                        ['attribute' => 'tx_id', 'value' => empty($model->tx_id) ? null : Html::a($model->tx_id, 'https://blockchain.info/tx/' . $model->tx_id, ['target' => '_blank']), 'format' => 'raw'],
                        'tx_confirmed:decimal',
                        //'tx_date:dateTime',
                        //'tx_check_date:dateTime',
                        'updatedAt:dateTime',
                        ['attribute' => 'status', 'value' => $model->statusColorText, 'format' => 'raw'],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
