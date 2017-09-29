<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

/* @var $bitcoin [] */

use yii\helpers\Html;

?>
<div class="row" style="margin-top: 20px">
    <div class="col-lg-6 col-lg-push-3 col-md-8 col-md-push-2 col-sm-6 col-sm-push-3 col-xs-8 col-xs-push-2">
        <?= Html::img('data:image/png;base64,' . $bitcoin['base64QR'], ['class' => 'img img-responsive img-thumbnail']) ?>
    </div>
</div>
