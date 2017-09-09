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

    if(Yii::$app->params['billing']['db']==='mongodb'){
        $addresses=BitcoinAddress::find()
            ->where([
                'status'=>BitcoinAddress::STATUS_USED,
                'updated_at'=>['$lte'=>new \MongoDB\BSON\UTCDateTime($point*1000)],
                'tx_id'=>null
            ])->all();
    }
    else {
        $addresses=BitcoinAddress::find()->where('status=:status AND updated_at<=:point AND tx_id IS NULL', [
            ':status'=>BitcoinAddress::STATUS_USED,
            ':point'=>$point
        ])->all();
    }


    if($addresses){
        $obj=[];
        foreach($addresses as $address){
            $address->release();
            $obj[]=$address->address;
        }
        $output = implode(', ', $obj);
    }

    /* Result */
    if (!empty($output)) {
        $log = new \common\models\TaskLog();
        $log->task = basename(__FILE__, '.php');
        $log->result = $output;
        $log->save();
    }


    /* delete old logs never bad */
    $period = 30 * 24 * 60 * 60; // 30 days
    $point = time() - $period;
    if(Yii::$app->params['mongodb']['taskLog']){
        \common\models\TaskLog::deleteAll([
            'task'=>basename(__FILE__, '.php'),
            'created_at'=>['$lte', new \MongoDB\BSON\UTCDateTime($point*1000)]
        ]);
    }
    else {
        \common\models\TaskLog::deleteAll('task=:task AND created_at<=:point', [
            ':task' => basename(__FILE__, '.php'),
            ':point' => $point
        ]);
    }


})->cron($time);