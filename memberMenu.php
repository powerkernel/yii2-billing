<?php

/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


use common\Core;
use common\widgets\SideMenu;

$menu=[
    'title'=>Yii::$app->getModule('billing')->t('Billing'),
    'icon'=> 'shopping-bag',
    'items'=>[
        ['icon' => 'info-square', 'label' => Yii::$app->getModule('billing')->t('Information'), 'url' => ['/billing/info'], 'active' => Core::checkMCA('billing', 'info', '*')],
        ['icon' => 'credit-card', 'label' => Yii::$app->getModule('billing')->t('Invoices'), 'url' => ['/billing/invoice'], 'active' => Core::checkMCA('billing', 'invoice', '*')],
        ['icon' => 'address-book', 'label' => Yii::$app->getModule('billing')->t('Addresses'), 'url' => ['/billing/address'], 'active' => Core::checkMCA('billing', 'address', '*')],
    ],
];
$menu['active']=SideMenu::isActive($menu['items']);
return [$menu];
