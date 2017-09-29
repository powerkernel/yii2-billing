<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace modernkernel\billing\controllers;

use backend\controllers\BackendController;
use common\Core;
use modernkernel\billing\models\Setting;
use Yii;
use yii\base\DynamicModel;

/**
 * SettingController
 */
class SettingController extends BackendController
{
    /**
     * setting page
     * @return string
     */
    public function actionIndex(){
        $attributes = Setting::loadAsArray();
        $tabs=Setting::find()->asArray()->distinct('group');

        $model=new DynamicModel($attributes);
        $settings = [];

        foreach ($attributes as $key=>$value) {
            $setting = Setting::find()->where(['key' => $key])->asArray()->one();
            $settings[$setting['group']][$key] = $setting;
            $model->$key = $setting['value'];

            if (!empty($rules = json_decode($setting['rules'], true))) {
                foreach ($rules as $rule => $conf) {
                    $model->addRule($key, $rule, $conf);
                }
            } else {
                $model->addRule($key, 'required');
            }

        }



        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($attributes as $key=>$oldValue) {
                $s = Setting::find()->where(['key'=>$key])->one();
                $s->value = $model->$key;
                if (!$s->save(false)) {
                    Yii::$app->session->setFlash('error', Yii::t('billing', 'Sorry, something went wrong. {ERRORS}.', ['ERRORS' => json_encode($s->errors)]));
                    break;
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('billing', 'Settings saved successfully.'));
        }


        return $this->render('index', [
            'model' => $model,
            'settings' => $settings,
            'tabs' => $tabs
        ]);
    }

    /**
     * update settings
     * @return \yii\web\Response
     */
    public function actionUpdate(){
        $s = [
            /* Merchant */
            ['key' => 'merchantName', 'value' => '', 'title' => 'Merchant Name', 'description' => 'Merchant name', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
            ['key' => 'merchantAddress', 'value' => '', 'title' => 'Merchant Address', 'description' => 'Merchant address', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
            ['key' => 'merchantCity', 'value' => '', 'title' => 'Merchant City', 'description' => 'Merchant city', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
            ['key' => 'merchantState', 'value' => '', 'title' => 'Merchant State', 'description' => 'Merchant state', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
            ['key' => 'merchantZip', 'value' => '', 'title' => 'Merchant Zip', 'description' => 'Merchant zip code', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
            ['key' => 'merchantCountry', 'value' => '', 'title' => 'Merchant Country', 'description' => 'Merchant country', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required'=>[], 'string' => []])],
            ['key' => 'merchantPhone', 'value' => '', 'title' => 'Merchant Phone', 'description' => 'Merchant phone', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
            ['key' => 'merchantEmail', 'value' => '', 'title' => 'Merchant Email', 'description' => 'Merchant email', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'email' => []])],

            /* Paypal */
            ['key' => 'paypalEmail', 'value' => '', 'title' => 'Paypal Email', 'description' => 'Paypal email address', 'group' => 'Paypal', 'type' => 'textInput', 'data' => json_encode(Core::getYesNoOption()), 'default' => '', 'rules' => json_encode(['string' => [], 'email' => []])],
            ['key' => 'paypalSandbox', 'value' => '', 'title' => 'Paypal Sandbox Mode', 'description' => 'Set yes to enable sandbox', 'group' => 'Paypal', 'type' => 'dropDownList', 'data' => json_encode(Core::getYesNoOption()), 'default' => '1', 'rules' => json_encode(['required' => [], 'boolean' => []])],
            ['key' => 'paypalClientID', 'value' => '', 'title' => 'Paypal Client ID', 'description' => 'Live client ID', 'group' => 'Paypal', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
            ['key' => 'paypalSecret', 'value' => '', 'title' => 'Paypal Secret', 'description' => 'Live secret string', 'group' => 'Paypal', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
            ['key' => 'paypalSandboxClientID', 'value' => '', 'title' => 'Paypal Sandbox Client ID', 'description' => 'Sandbox client ID', 'group' => 'Paypal', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
            ['key' => 'paypalSandboxSecret', 'value' => '', 'title' => 'Paypal Sandbox Secret', 'description' => 'Sandbox secret string', 'group' => 'Paypal', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],

            /* Bitcoin */
            ['key' => 'btcPaymentTime', 'value' => '', 'title' => 'BTC Payment Timeout', 'description' => 'Payment timeout in seconds', 'group' => 'Bitcoin', 'type' => 'textInput', 'data' => '[]', 'default' => '900', 'rules' => json_encode(['integer' => ['min'=>300, 'max'=>3600]])],
            ['key' => 'btcWalletXPub', 'value' => '', 'title' => 'BTC Wallet xPub', 'description' => 'Wallet extended public key', 'group' => 'Bitcoin', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],

            /* order API */
            ['key' => 'currencyLayerAPI', 'value' => '', 'title' => 'Currency Layer API', 'description' => 'Access API key for currency conversion', 'group' => 'MISC', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],


        ];
        $settings = Setting::find()->all();
        foreach ($settings as $setting) {
            if (!in_array($setting->key, array_column($s, 'key'))) {
                $setting->delete();
            }
        }

        /* sync */
        $unsave=[];
        foreach ($s as $i => $setting) {
            $conf = Setting::find()->where(['key'=>$setting['key']])->one();
            if (!$conf) {
                $conf = new Setting();
                $conf->key = $setting['key'];
                $conf->value = $setting['value'];
            }

            $conf->title = $setting['title'];
            $conf->group = $setting['group'];
            $conf->type = $setting['type'];
            $conf->data = $setting['data'];
            $conf->default = $setting['default'];
            $conf->rules = $setting['rules'];
            $conf->key_order = (int)$i;
            if(!$conf->save()){
                $unsave[]=$conf->key;
                //var_dump($conf->errors);
            }
        }

        if(is_a(Yii::$app, '\yii\web\Application')){
            if(empty($unsave)){
                Yii::$app->session->setFlash('success', Yii::t('app', 'All settings has been updated.'));
            }
            else {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Some setting(s) can not be updated: {SETTINGS}', ['SETTINGS'=>implode(', ', $unsave)]));
            }
        }

        return $this->redirect(['index']);
    }
}
