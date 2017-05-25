<?php

/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


use common\Core;
use common\widgets\SideMenu;

$menu=[
    'title'=>Yii::$app->getModule('billing')->t('Billing'),
    'icon'=> 'vcard',
    'items'=>[
        ['icon' => 'address-card-o', 'label' => Yii::$app->getModule('billing')->t('My Information'), 'url' => ['/billing/info'], 'active' => Core::checkMCA('billing', 'info', '*')],
        ['icon' => 'credit-card', 'label' => Yii::$app->getModule('billing')->t('My Invoices'), 'url' => ['/billing/invoice'], 'active' => Core::checkMCA('billing', 'invoice', '*')],
    ],
];
$menu['active']=SideMenu::isActive($menu['items']);
return [$menu];