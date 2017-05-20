<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

use common\Core;
use modernkernel\billing\models\BitcoinAddress;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '30 * * * *';

$schedule->call(function (\yii\console\Application $app) {

    /* get confirmed <3 address  */
    $addresses=BitcoinAddress::find()->where('tx_confirmed<3 AND status!=:status', [':status'=>BitcoinAddress::STATUS_NEW])->all();

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