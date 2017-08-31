<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use modernkernel\billing\models\Coupon;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\Coupon */

$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->code;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="coupon-view">
    <div class="box box-info">
        <div class="box-body">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'code',
                        'currency',
                        [
                            'attribute' => 'discount',
                            'value' => $model->discount_type == Coupon::DISCOUNT_TYPE_PERCENT ? Yii::$app->formatter->asPercent($model->discount/100):Yii::$app->formatter->asCurrency($model->discount, $model->currency)
                        ],
                        'beginAt:date',
                        'endAt:date',
                        [
                            'attribute' => 'quantity',
                            'value' => $model->quantity==-1?Yii::$app->getModule('billing')->t('Unlimited'):Yii::$app->formatter->asDecimal($model->quantity),
                        ],
                        'reuse:boolean',
                        ['attribute' => 'status', 'value' => $model->statusColorText, 'format' => 'raw'],
                        'createdAt:dateTime',
                        'updatedAt:dateTime',
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
