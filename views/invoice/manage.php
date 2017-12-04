<?php

use powerkernel\billing\models\Invoice;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel powerkernel\billing\models\InvoiceSearch */
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
<div class="invoice-index">
    <div class="box box-primary">
        <div class="box-body">
            <?php Pjax::begin(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],

                        'id_invoice',
                        //'account.fullname',
                        //['attribute' => 'fullname', 'value' => 'account.fullname'],
                        //['attribute' => 'subtotal', 'value' => function ($model){return Yii::$app->formatter->asCurrency($model->subtotal, $model->currency);}],
                        //['attribute' => 'discount', 'value' => function ($model){return Yii::$app->formatter->asCurrency($model->discount, $model->currency);}],
                        //['attribute' => 'tax', 'value' => function ($model){return Yii::$app->formatter->asCurrency($model->tax, $model->currency);}],
                        ['attribute' => 'total', 'value' => function ($model) {
                            return Yii::$app->formatter->asCurrency($model->total, $model->currency);
                        }],
                        // 'currency',
                        // 'status',
                        // 'created_at',
                        // 'updated_at',
                        ['attribute' => 'created_at', 'value' => 'createdAt', 'format' => 'dateTime', 'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'created_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']])],
                        ['attribute' => 'status', 'value' => function ($model) {
                            return $model->getStatusColorText();
                        }, 'filter' => Invoice::getStatusOption(), 'format' => 'raw'],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{show}',
                            'buttons' => [
                                'show' => function ($url) {
                                    $show = Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'View'),
                                        'data-pjax' => '0',
                                    ]);
                                    return $show;
                                }
                            ]
                        ],
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \powerkernel\fontawesome\Icon::widget(['icon' => 'refresh fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>
</div>
