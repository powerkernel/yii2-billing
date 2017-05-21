<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '6 6 * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* get used addresses, but no tx > 3 days  */
    $now=time();
    $days=3*24*3600;
    $point=$now-$days;
    $addresses=BitcoinAddress::find()->where('status=:status AND updated_at<=:point AND tx_id IS NULL', [
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

    $log = new \common\models\TaskLog();
    $log->task = basename(__FILE__, '.php');
    $log->result = $output;
    $log->save();
    /* delete old logs never bad */
    $period = 7 * 24 * 60 * 60; // 7 days
    $point = time() - $period;
    \common\models\TaskLog::deleteAll('task=:task AND created_at<=:point', [
        ':task' => basename(__FILE__, '.php'),
        ':point' => $point
    ]);


})->cron($time);