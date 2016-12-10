<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
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
                        'f_name',
                        'l_name',
                        'address',
                        'address2',
                        'city',
                        'state',
                        'zip',
                        'country',
                        'phone',
                        'status',
                        'created_at:date',
                        'updated_at:date',
                    ],
                ]) ?>
            </div>
            <p>
                <?= Html::a(Yii::t('billing', 'Update'), ['update', 'id' => $model->id_account], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('billing', 'Delete'), ['delete', 'id' => $model->id_account], [
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
