<?php

use modernkernel\billing\models\Setting;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */
/* @var $attributes [] */
/* @var $tabs [] */
/* @var $settings [] */

$this->title = Yii::t('billing', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing', 'Billing'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="billing-setting">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => ['horizontalCssClasses' => [
            'offset' => '',
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-6',
            'error' => '',
            'hint' => 'col-sm-4',
        ]],
    ]); ?>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php foreach ($tabs as $i => $tab): ?>
                <li class="<?= $i == 0 ? 'active' : '' ?>">
                    <a href="#<?= $tab ?>" data-toggle="tab"
                       aria-expanded="<?= $i == 0 ? 'true' : 'false' ?>"><?= $tab ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($tabs as $i => $tab): ?>
                <div class="tab-pane <?= $i == 0 ? 'active' : '' ?>" id="<?= $tab ?>">
                    <?php foreach ($settings[$tab] as $key => $setting): ?>

                        <?php if ($setting['type'] == 'textInput'): ?>
                            <?= $form->field($model, $key)->textInput()->label($setting['key'])->hint($setting['title']) ?>
                        <?php endif; ?>

                        <?php if ($setting['type'] == 'textarea'): ?>
                            <?= $form->field($model, $key)->textarea()->label($setting['key'])->hint($setting['title']) ?>
                        <?php endif; ?>

                        <?php if ($setting['type'] == 'passwordInput'): ?>
                            <?= $form->field($model, $key)->passwordInput()->label($setting['key'])->hint($setting['title']) ?>
                        <?php endif; ?>

                        <?php if ($setting['type'] == 'dropDownList'): ?>
                            <?=
                            $form->field($model, $key)->dropDownList(in_array($setting['data'], ['{TIMEZONE}', '{LOCALE}']) ? Setting::getListData($setting['data']) : json_decode($setting['data'], true))->label($setting['key'])->hint($setting['title'])
                            ?>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div>
                <hr/>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <?= Html::submitButton(Yii::t('billing', 'Save Settings'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>