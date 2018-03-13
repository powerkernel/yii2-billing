<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel powerkernel\billing\models\BillingInfoSearch */
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
<div class="billing-info-index">
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

                        //'id_account',
                        'company',
                        'f_name',
                        'l_name',
                        'address',
                        // 'address2',
                        // 'city',
                        // 'state',
                        // 'zip',
                        'phone',
                        'country',

                        // 'status',
                        // 'created_at',
                        // 'updated_at',
                        //['attribute' => 'created_at', 'value' => 'created_at', 'format' => 'dateTime', 'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'created_at', 'dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control']])],
                        //['attribute' => 'status', 'value' => function ($model){return $model->statusText;}, 'filter'=>''],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => ['style' => 'min-width: 70px']
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
