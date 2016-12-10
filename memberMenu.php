<?php

/**
 * @author: Harry Tang (giaduy@gmail.com)
 * @link: http://www.greyneuron.com
 * @copyright: Grey Neuron
 */


use common\Core;

return [
    ['label' => Yii::$app->getModule('billing')->t('Billing')],
    ['icon' => 'vcard', 'label' => Yii::$app->getModule('billing')->t('My Information'), 'url' => ['/billing/info/manage'], 'active' => Core::checkMCA('billing', 'info', '*')],
    ['icon' => 'credit-card', 'label' => Yii::$app->getModule('billing')->t('My Invoices'), 'url' => ['/billing/invoice/manage'], 'active' => Core::checkMCA('billing', 'invoice', '*')],
    ['icon' => 'google-wallet', 'label' => Yii::$app->getModule('billing')->t('My Wallet'), 'url' => ['/billing/wallet/index'], 'active' => Core::checkMCA('billing', 'wallet', '*')],
];