<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '*/15 * * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* update confirmations */
    $addresses = BitcoinAddress::find()->where(['status'=>BitcoinAddress::STATUS_UNCONFIRMED])->all();

    if ($addresses) {
        $obj = [];
        foreach ($addresses as $address) {
            $address->checkPayment();
            $obj[] = $address->id;
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
    $period = 30* 24 * 60 * 60; // 30 day
    $point = time() - $period;
    \common\models\TaskLog::deleteAll('task=:task AND created_at<=:point', [
        ':task' => basename(__FILE__, '.php'),
        ':point' => $point
    ]);
})->cron($time);