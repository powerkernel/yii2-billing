<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
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
$css=file_get_contents(__DIR__.'/manage.css');
$this->registerCss($css);
$this->context->layout = Yii::$app->view->theme->basePath . '/account.php';
?>
<div class="billing-address-manage">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php Pjax::begin(); ?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_grid',
            'layout' => "<div class=\"row\">{items}</div>{pager}"
        ]) ?>

    <?php Pjax::end(); ?>


    <p>
        <?= Html::a(Yii::t('billing', 'Add Address'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


</div>
