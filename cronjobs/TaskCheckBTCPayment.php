<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '*/2 * * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* check payment within 15 min */
    $now = time();
    $period = 900;
    $point = $now - $period;
    $addresses = BitcoinAddress::find()
        ->where('status=:used AND updated_at>=:point',
        [
            ':used' => BitcoinAddress::STATUS_USED,
            ':point' => $point
        ])->all();

    if ($addresses) {
        $obj = [];
        foreach ($addresses as $address) {
            $address->checkPayment();
            $obj[] = $address->id;
        }
        $output = $app->getModule('billing')->t('Addresses checked: {ADDR}', ['ADDR'=>implode(', ', $obj)]);
    }

    /* Result */
    if (empty($output)) {
        $output = $app->getModule('billing')->t('No BTC address need to check.');
    }
    $log=new \common\models\TaskLog();
    $log->task=basename(__FILE__, '.php');
    $log->result=$output;
    $log->save();

    //echo $app->getModule('billing')->t(basename(__FILE__, '.php') . ': ' . $output . "\n\n");
})->cron($time);