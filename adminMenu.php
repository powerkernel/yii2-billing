<?php

/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


use common\Core;

return [
    ['label' => Yii::$app->getModule('billing')->t('Billing')],
    ['icon' => 'vcard', 'label' => Yii::$app->getModule('billing')->t('Customers'), 'url' => ['/billing/info/index'], 'active' => Core::checkMCA('billing', 'info', '*')],
    ['icon' => 'money', 'label' => Yii::$app->getModule('billing')->t('Invoices'), 'url' => ['/billing/invoice/index'], 'active' => Core::checkMCA('billing', 'invoice', '*')],
    ['icon' => 'university', 'label' => Yii::$app->getModule('billing')->t('Banks'), 'url' => ['/billing/bank/index'], 'active' => Core::checkMCA('billing', 'bank', '*')],
    ['icon' => 'btc', 'label' => Yii::$app->getModule('billing')->t('Bitcoin Address'), 'url' => ['/billing/bitcoin/index'], 'active' => Core::checkMCA('billing', 'bitcoin', '*')],
];