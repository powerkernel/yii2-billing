<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace modernkernel\billing\components;

use yii\web\AssetBundle;

/**
 * Class PaypalJSAsset
 * @package modernkernel\billing\components
 */
class PaypalJSAsset extends AssetBundle
{
    public $js = [
        'https://www.paypalobjects.com/api/checkout.js',
    ];
    public $jsOptions=['data-version-4'=>true];
}