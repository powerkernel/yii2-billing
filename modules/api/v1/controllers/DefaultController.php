<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace powerkernel\billing\modules\api\v1\controllers;

/**
 * Class DefaultController
 */
class DefaultController extends \yii\rest\Controller
{
    /**
     * @return array
     */
    public function actionIndex(){
        return [
            'status'=>'ok',
            'version'=>'1.0'
        ];
    }
}