<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\BillingInfo */


$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_account, 'url' => ['view', 'id' => $model->id_account]];
$this->params['breadcrumbs'][] = Yii::t('billing', 'Update');

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="billing-info-update">
    <div class="box box-primary">
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
