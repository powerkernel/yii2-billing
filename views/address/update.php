<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

/* @var $this yii\web\View */
/* @var $model modernkernel\billing\models\Address */


$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = Yii::t('billing', 'Update');

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
$this->context->layout = Yii::$app->view->theme->basePath . '/account.php';
?>
<div class="address-update">
    <div class="box box-primary">
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
