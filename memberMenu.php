<?php

/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


use common\Core;

return [
    ['label' => Yii::$app->getModule('billing')->t('Billing')],
    ['icon' => 'vcard', 'label' => Yii::$app->getModule('billing')->t('My Information'), 'url' => ['/billing/info'], 'active' => Core::checkMCA('billing', 'info', '*')],
    ['icon' => 'credit-card', 'label' => Yii::$app->getModule('billing')->t('My Invoices'), 'url' => ['/billing/invoice'], 'active' => Core::checkMCA('billing', 'invoice', '*')],
    //['icon' => 'google-wallet', 'label' => Yii::$app->getModule('billing')->t('My Wallet'), 'url' => ['/billing/wallet/index'], 'active' => Core::checkMCA('billing', 'wallet', '*')],
];