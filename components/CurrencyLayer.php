<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace modernkernel\billing\components;


use common\models\Setting;
use Yii;
use yii\httpclient\Client;

/**
 * Class CurrencyLayer
 * @package modernkernel\billing\components
 */
class CurrencyLayer
{
    CONST END_POINT = 'http://www.apilayer.net/api/live';
    public $quotes;

    /**
     * CurrencyLayer constructor.
     */
    public function __construct()
    {
        $access_key=Setting::getValue('currencyLayerAPI');
        if(!empty($access_key)){
            $this->init($access_key);
        }
    }

    /**
     * @param $access_key
     */
    protected function init($access_key){
        $cache='currency-layer';
        $client = new Client();
        $response = Yii::$app->cache->get($cache);
        if ($response === false) {
            $response = $client->createRequest()
                ->setMethod('get')
                ->setUrl(self::END_POINT)
                ->setData(['access_key' => $access_key])
                ->send();
            Yii::$app->cache->set($cache, $response, 43200); // 12 hours
        }
        if ($response->isOk && !empty($response->data['quotes'])) {
            $this->quotes = $response->data['quotes'];
        }
    }


    /**
     * @param $from
     * @param $to
     * @param $amount
     * @return bool|float|int
     */
    public function convert($from, $to, $amount)
    {
        //if($amount==0) return $amount;

        if($from!='USD'){
            $amount=$this->convertToUSD($from, $amount);
        }
        if (!empty($this->quotes['USD' . $to])) {
            return $amount*$this->quotes['USD' . $to];
        }
        return false;
    }


    /**
     * @param $from
     * @param $amount
     * @return bool|float|int
     */
    public function convertToUSD($from, $amount)
    {
        //if($amount==0) return $amount;

        if (!empty($this->quotes['USD' . $from])) {
            return $amount / $this->quotes['USD' . $from];
        }
        return false;
    }

}