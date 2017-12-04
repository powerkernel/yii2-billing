<?php
/**
 * @var \powerkernel\scheduling\Schedule $schedule
 */

use common\Core;
use powerkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '*/5 * * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* check payment within 15 min */
    $now = time();
    $period = 999; // margin is always better :)
    $point = $now - $period;

    if(Yii::$app->getModule('billing')->params['db']==='mongodb'){
        $addresses = BitcoinAddress::find()
            ->where([
                'status'=>BitcoinAddress::STATUS_USED,
                'updated_at'=>['$gte'=>new \MongoDB\BSON\UTCDateTime($point*1000)]
            ])->all();
    }
    else {
        $addresses = BitcoinAddress::find()
            ->where('status=:used AND updated_at>=:point',
                [
                    ':used' => BitcoinAddress::STATUS_USED,
                    ':point' => $point
                ])->all();
    }


    if ($addresses) {
        $obj = [];
        foreach ($addresses as $address) {
            $address->checkPayment();
            $obj[] = $address->address;
        }
        $output = $app->getModule('billing')->t('Addresses checked: {ADDR}', ['ADDR' => implode(', ', $obj)]);
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