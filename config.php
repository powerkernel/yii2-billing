<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

$local = require(Yii::$aliases['@common'] . '/config/params-local.php');
if (!isset($local['yii2-billing'])) {
    $local['yii2-billing'] = [];
}

$default = [
    'db' => 'mysql'
];
return [
    'params' => yii\helpers\ArrayHelper::merge(
        $default,
        $local['yii2-billing']
    )
];