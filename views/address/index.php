<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel powerkernel\billing\models\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


/* breadcrumbs */
$this->params['breadcrumbs'][] = Yii::$app->getModule('billing')->t('Billing');
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="address-index">
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
                        ['attribute' => 'id_account', 'value' => function ($model){return Html::a($model->account->fullname, ['/account/view', 'id'=>(string)$model->account->id], ['data-pjax'=>0]);}, 'format'=>'raw'],

                        'contact_name',
                        //'street_address_1',
                        // 'street_address_2',
                        'city',
                        'state',
                        'zip_code',
                        'country',
                        'phone',
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
                        //['attribute' => 'status', 'value' => function ($model){return $model->statusText;}, 'filter'=>''],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            //'contentOptions' => ['style' => 'min-width: 70px'],
                            'template'=>'{view}'
                        ],
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>

        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \powerkernel\fontawesome\Icon::widget(['name' => 'sync', 'styling'=>'fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>
</div>
