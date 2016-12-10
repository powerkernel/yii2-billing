<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\BillingInfo */
/* @var $account \common\models\Account */


/* breadcrumbs */
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="billing-info-create">
    <div class="box box-success">
        <div class="box-body">
            <div class="alert alert-info">
                <?= Yii::$app->getModule('billing')->t('Adding billing information for {EMAIL}', ['EMAIL'=>$account->email]) ?>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
