<?php

use modernkernel\billing\models\BitcoinAddress;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modernkernel\billing\models\BitcoinAddressSearch */
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
<div class="bitcoin-address-index">
    <div class="box box-primary">
        <div class="box-body">


            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


            <?php Pjax::begin(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
                        'id',
                        ['attribute' => 'address', 'value' => function ($model){return Html::a($model->address, Yii::$app->urlManager->createUrl(['/billing/bitcoin/view', 'id'=>$model->id]), ['title'=>Yii::$app->getModule('billing')->t('View BTC Address'), 'data-pjax'=>0]) ;}, 'format'=>'raw'],
                        'id_invoice',
                        //'id_account',

                        'request_balance',
                        'total_received',
                        // 'tx_id',
                        // 'tx_date',
                        // 'tx_confirmed',
                        // 'tx_check_date',
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
                        ['attribute' => 'status', 'value' => function ($model){return $model->statusColorText;}, 'filter'=> BitcoinAddress::getStatusOption(), 'format'=>'raw'],
                        //['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
            <p>
                <?= Html::a(Yii::t('billing', 'Generate Addresses'), ['generate'], ['class' => 'btn btn-success']) ?>
            </p>

        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <?= \modernkernel\fontawesome\Icon::widget(['icon' => 'refresh fa-spin']) ?>
        </div>
        <!-- end loading -->
    </div>
</div>
