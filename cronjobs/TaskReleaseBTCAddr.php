<?php
/**
 * @var \powerkernel\scheduling\Schedule $schedule
 */

use common\Core;
use powerkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '6 6 * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* get used addresses, but no tx > 3 days  */
    $now = time();
    $days = 3 * 24 * 3600;
    $point = $now - $days;

    $addresses = BitcoinAddress::find()
        ->where([
            'status' => BitcoinAddress::STATUS_USED,
            'updated_at' => ['$lte' => new \MongoDB\BSON\UTCDateTime($point * 1000)],
            'tx_id' => null
        ])->all();


    if ($addresses) {
        $obj = [];
        foreach ($addresses as $address) {
            $address->release();
            $obj[] = $address->address;
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
    \common\models\TaskLog::deleteAll([
        'task' => basename(__FILE__, '.php'),
        'created_at' => ['$lte', new \MongoDB\BSON\UTCDateTime($point * 1000)]
    ]);


})->cron($time);
