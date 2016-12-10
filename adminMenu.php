<?php

/**
 * @author: Harry Tang (giaduy@gmail.com)
 * @link: http://www.greyneuron.com
 * @copyright: Grey Neuron
 */


use common\Core;

return [
    ['label' => Yii::$app->getModule('billing')->t('Billing')],
    ['icon' => 'vcard', 'label' => Yii::$app->getModule('billing')->t('Customers'), 'url' => ['/billing/info/index'], 'active' => Core::checkMCA('billing', 'info', '*')],
    ['icon' => 'money', 'label' => Yii::$app->getModule('billing')->t('Invoices'), 'url' => ['/billing/invoice/index'], 'active' => Core::checkMCA('billing', 'invoice', '*')],
];