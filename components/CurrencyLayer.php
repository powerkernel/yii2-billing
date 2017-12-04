<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace powerkernel\billing\components;


use common\components\CurrencyFraction;
use Yii;
use yii\httpclient\Client;

/**
 * Class CurrencyLayer
 * @package powerkernel\billing\components
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
        $access_key=\powerkernel\billing\models\Setting::getValue('currencyLayerAPI');
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
        if($from!='USD'){
            $amount=$this->convertToUSD($from, $amount);
        }
        if (!empty($this->quotes['USD' . $to])) {
            $result=$amount*$this->quotes['USD' . $to];
            if($to=='VND'){
                $result=ceil($result/1000)*1000; // round up
            }
            return round($result, CurrencyFraction::getFraction($to));
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
            return round($amount / $this->quotes['USD' . $from], CurrencyFraction::getFraction('USD'));
        }
        return false;
    }

}