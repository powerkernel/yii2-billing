<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\Address */

$this->params['breadcrumbs'][] = Yii::$app->getModule('billing')->t('Billing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::$app->getModule('billing')->t('View');

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="address-view">
    <div class="box box-info">
        <div class="box-body">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //'_id',
                        ['attribute' => 'id_account', 'value' => Html::a($model->account->fullname, ['/account/view', 'id'=>(string)$model->account->id]), 'format'=>'raw'],
                        'contact_name',
                        'street_address_1',
                        'street_address_2',
                        'city',
                        'state',
                        'zip_code',
                        'country',
                        'phone',
                        'status',
                        'createdAt:dateTime',
                        'updatedAt:dateTime',
                        //['attribute' => 'status', 'value' => $model->statusColorText, 'format'=>'raw'],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
