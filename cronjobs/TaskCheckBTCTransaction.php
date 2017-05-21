<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '30 * * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* update confirmations */
    $addresses = BitcoinAddress::find()
        ->where('status=:unconfirmed AND tx_confirmed<3',
            [
                ':unconfirmed' => BitcoinAddress::STATUS_UNCONFIRMED,
            ])->all();

    if ($addresses) {
        $obj = [];
        foreach ($addresses as $address) {
            $address->checkPayment();
            $obj[] = $address->id;
        }
        $output = $app->getModule('billing')->t('Addresses checked: {ADDR}', ['ADDR' => implode(', ', $obj)]);
    }

    /* Result */
    if (empty($output)) {
        $output = $app->getModule('billing')->t('No BTC address need to check.');
    }
    $log = new \common\models\TaskLog();
    $log->task = basename(__FILE__, '.php');
    $log->result = $output;
    $log->save();

    /* delete old logs never bad */
    $period = 24 * 60 * 60; // 1 day
    $point = time() - $period;
    \common\models\TaskLog::deleteAll('task=:task AND created_at<=:point', [
        ':task' => basename(__FILE__, '.php'),
        ':point' => $point
    ]);
})->cron($time);