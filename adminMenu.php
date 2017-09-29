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
        ['icon' => 'money', 'label' => Yii::$app->getModule('billing')->t('Invoices'), 'url' => ['billing/invoice/index'], 'active' => Core::checkMCA('billing', 'invoice', '*')],
        ['icon' => 'users', 'label' => Yii::$app->getModule('billing')->t('Customers'), 'url' => ['billing/info/index'], 'active' => Core::checkMCA('billing', 'info', '*')],
        ['icon' => 'address-book', 'label' => Yii::$app->getModule('billing')->t('Addresses'), 'url' => ['/billing/address/index'], 'active' => Core::checkMCA('billing', 'address', '*')],
        ['icon' => 'gift', 'label' => Yii::$app->getModule('billing')->t('Coupons'), 'url' => ['billing/coupon/index'], 'active' => Core::checkMCA('billing', 'coupon', '*')],
        ['icon' => 'university', 'label' => Yii::$app->getModule('billing')->t('Banks'), 'url' => ['billing/bank/index'], 'active' => Core::checkMCA('billing', 'bank', '*')],
        ['icon' => 'btc', 'label' => Yii::$app->getModule('billing')->t('Bitcoin'), 'url' => ['billing/bitcoin/index'], 'active' => Core::checkMCA('billing', 'bitcoin', '*')],
        ['icon' => 'gears', 'label' => Yii::$app->getModule('billing')->t('Settings'), 'url' => ['billing/setting/index'], 'active' => Core::checkMCA('billing', 'setting', 'index')],
    ],
];
$menu['active']=SideMenu::isActive($menu['items']);
return [$menu];