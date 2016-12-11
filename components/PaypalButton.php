<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace modernkernel\billing\components;


use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class PaypalButton
 * @package modernkernel\billing\components
 */
class PaypalButton extends Widget
{


    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerJS();
        echo Html::tag('div', '', ['id'=>'paypal-button']);
    }

    /**
     * Registers plugin and the related events
     */
    protected function registerPlugin()
    {
        $view = $this->getView();
        PaypalJSAsset::register($view);
    }

    /**
     * Register JS
     */
    protected function registerJS()
    {
        $this->registerPlugin();
        $create=Yii::$app->urlManager->createAbsoluteUrl(['/billing/invoice/create-payment']);
        $execute=Yii::$app->urlManager->createAbsoluteUrl(['/billing/invoice/execute-payment']);


        $js=<<<EOB
paypal.Button.render({  
        env: 'sandbox', // production|sandbox Specify 'sandbox' for the test environment
    
        payment: function(resolve, reject) {
            var CREATE_PAYMENT_URL = '{$create}';
            paypal.request.post(CREATE_PAYMENT_URL)
                .then(function(data) { resolve(data.paymentID); })
                .catch(function(err) { reject(err); });
        },

        onAuthorize: function(data) {
            // Note: you can display a confirmation page before executing
            var EXECUTE_PAYMENT_URL = '{$execute}';
            paypal.request.post(EXECUTE_PAYMENT_URL, { paymentID: data.paymentID, payerID: data.payerID })                    
                .then(function(data) { /* Go to a success page */ })
                .catch(function(err) { /* Go to an error page  */ });
        }
            
    }, '#paypal-button');
EOB;

        $this->view->registerJs($js);

    }
}