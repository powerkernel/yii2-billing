<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\BillingInfo */

$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="billing-info-view">
    <div class="box box-info">
        <div class="box-body">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //'id_account',
                        'company',
                        'tax_id',
                        'f_name',
                        'l_name',
                        'address',
                        'address2',
                        'city',
                        'state',
                        'zip',
                        'country',
                        'phone',
                        ['attribute' => 'status', 'value' => $model->statusColorText, 'format'=>'raw'],
                        'createdAt:date',
                        'updatedAt:date',
                    ],
                ]) ?>
            </div>
            <p>
                <?= Html::a(Yii::t('billing', 'Update'), ['update', 'id' => (string)$model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('billing', 'Delete'), ['delete', 'id' => (string)$model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('billing', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
    </div>
</div>
