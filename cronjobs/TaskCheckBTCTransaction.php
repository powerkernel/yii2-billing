<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '30 * * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* check payment 15 min */
    $now=time();
    $period=900;
    $point=$now-$period;
    $addresses=BitcoinAddress::find()->where('status!=:status AND updated_at>=:point', [
        ':status'=>BitcoinAddress::STATUS_USED,
        ':point'=>$point
    ])->all();

    if($addresses){
        $obj=[];
        foreach($addresses as $address){
            $address->checkPayment();
            $obj[]=$address->id;
        }
        $output = implode(', ', $obj);
    }

    /* and update confirmations */
    $addresses=BitcoinAddress::find()->where('status!=:status AND tx_confirmed<3', [
        ':status'=>BitcoinAddress::STATUS_UNCONFIRMED,
    ])->all();
    if($addresses){
        $obj=[];
        foreach($addresses as $address){
            $address->checkPayment();
            $obj[]=$address->id;
        }
        $output = implode(', ', $obj);
    }

    /* Result */
    if (empty($output)) {
        $output = $app->getModule('billing')->t('No BTC address need to check.');
    }
    echo $app->getModule('billing')->t(basename(__FILE__, '.php') . ': ' . $output . "\n\n");
})->cron($time);