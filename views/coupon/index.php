<?php

use modernkernel\billing\models\Coupon;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modernkernel\billing\models\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


/* breadcrumbs */
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="coupon-index">
    <div class="box box-primary">
        <div class="box-body">


            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


            <?php Pjax::begin(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'code',
                        [
                            'attribute' => 'discount',
                            'value' => function ($model) {
                                return $model->discount_type == Coupon::DISCOUNT_TYPE_PERCENT ? Yii::$app->formatter->asPercent($model->discount / 100) : Yii::$app->formatter->asCurrency($model->discount, $model->currency);
                            }
                        ],
                        [
                            'attribute' => 'begin_at',
                            'value' => 'begin_at',
                            'format' => 'date',
                            'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'begin_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']]),
                            'contentOptions' => ['style' => 'min-width: 80px']
                        ],
                        [
                            'attribute' => 'end_at',
                            'value' => 'end_at',
                            'format' => 'date',
                            'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'end_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']]),
                            'contentOptions' => ['style' => 'min-width: 80px']
                        ],
                        [
                            'attribute' => 'quantity',
                            'value' => function ($model) {
                                return $model->quantity == -1 ? Yii::$app->getModule('billing')->t('Unlimited') : Yii::$app->formatter->asDecimal($model->quantity);
                            },
                        ],
                        //'',
                        // 'reuse',
                        // 'status',
                        // 'created_at',
                        // 'updated_at',
                        //[
                        //    'attribute' => 'created_at',
                        //    'value' => 'created_at',
                        //    'format' => 'dateTime',
                        //    'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'created_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']]),
                        //    'contentOptions'=>['style'=>'min-width: 80px']
                        //],
                        ['attribute' => 'status', 'value' => function ($model) {
                            return $model->statusColorText;
                        }, 'filter' => Coupon::getStatusOption(), 'format' => 'raw'],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => ['style' => 'min-width: 70px']
                        ],
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
            <p>
                <?= Html::a(Yii::t('billing', 'Add Coupon'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \modernkernel\fontawesome\Icon::widget(['icon' => 'refresh fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>
</div>
