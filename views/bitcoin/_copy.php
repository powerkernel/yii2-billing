<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

/* @var $bitcoin [] */

?>
<div id="copy-tab" class="no-color-palette-set" style="margin-top: 20px">
    <p class="text-center"><?= Yii::$app->getModule('billing')->t('To complete your payment, please send {BTC} BTC to the address below.', ['BTC' => $bitcoin['amount']]) ?></p>

    <div class="row" style="">
        <div class="col-sm-10 col-sm-push-1 col-xs-12">
            <div class="well text-center" style="padding: 20px;">
                <div>
                    <div class="text-muted"><?= Yii::$app->getModule('billing')->t('AMOUNT') ?></div>
                    <div class="text-bold" style="font-size: 1.3em"><?= $bitcoin['amount'] ?> BTC</div>
                </div>
                <div><hr /></div>
                <div>
                    <div class="text-muted"><?= Yii::$app->getModule('billing')->t('ADDRESS') ?></div>
                    <div class="bg-gray color-palette text-bold btn btn-default" id="btc-address" data-copied="<?= Yii::$app->getModule('billing')->t('Address copied') ?>" data-addr="<?= $bitcoin['address'] ?>"><?= $bitcoin['address'] ?></div>

                </div>
            </div>
        </div>
    </div>
</div>
