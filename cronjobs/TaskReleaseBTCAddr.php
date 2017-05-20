<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '0 23 * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* get used addresses, but no tx with in 3 days  */
    $now=time();
    $days=3*24*3600;
    $point=$now-$days;
    $addresses=BitcoinAddress::find()->where('status=:status AND updated_at>=:point AND tx_id IS NULL', [
        ':status'=>BitcoinAddress::STATUS_USED,
        ':point'=>$point
    ])->all();

    if($addresses){
        $obj=[];
        foreach($addresses as $address){
            $address->release();
            $obj[]=$address->id;
        }
        $output = implode(', ', $obj);
    }

    /* Result */
    if (empty($output)) {
        $output = $app->getModule('billing')->t('No BTC address need to release.');
    }
    echo $app->getModule('billing')->t(basename(__FILE__, '.php') . ': ' . $output . "\n\n");
})->cron($time);