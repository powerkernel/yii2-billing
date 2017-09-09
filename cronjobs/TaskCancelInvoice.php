<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\Invoice;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '7 7 * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* get pending invoice > 7 days  */
    $now = time();
    $days = 7 * 24 * 3600;
    $point = $now - $days;


    if (Yii::$app->params['billing']['db'] === 'mongodb') {
        $invoices = Invoice::find()
            ->where([
                'status' => Invoice::STATUS_PENDING,
                'updated_at' => ['$lte' => new \MongoDB\BSON\UTCDateTime($point*1000)]
            ])->all();
    } else {
        $invoices = Invoice::find()->where('status=:status AND updated_at<=:point', [
            ':status' => Invoice::STATUS_PENDING,
            ':point' => $point
        ])->all();
    }

    if ($invoices) {
        $obj = [];
        foreach ($invoices as $invoice) {
            $invoice->cancel();
            $obj[] = $invoice->id;
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

    unset($app);

})->cron($time);