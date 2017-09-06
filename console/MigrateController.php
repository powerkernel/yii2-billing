<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace modernkernel\billing\console;

use MongoDB\BSON\UTCDateTime;
use yii\db\Query;

/**
 * Class MigrateController
 * @package modernkernel\billing\console
 */
class MigrateController extends \yii\console\Controller
{
    public function actionIndex()
    {
//        $this->btc();
//        $this->bank();
//        $this->coupon();
//        $this->info();
        //$this->item();
        //$this->invoice();
        $this->setting();
    }

    /**
     * copy setting to MongoDB
     */
    protected function setting(){
        echo "Migrating Settings...\n";
        $rows = (new Query())->select('*')->from('{{%billing_settings}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('billing_settings');
        $collection->remove();
        foreach ($rows as $row) {
            $collection->insert([
                'key' => $row['key'],
                'title' => $row['title'],
                'value' => $row['value'],
                'type' => $row['type'],
                'data' => $row['data'],
                'rules' => $row['rules'],
            ]);
        }
        echo "Settings migration completed.\n";
    }

    /**
     * copy invoice to MongoDB
     */
    protected function invoice()
    {
        echo "Migrating Invoice...\n";
        $rows = (new Query())->select('*')->from('{{%billing_invoice}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('billing_invoice');
        $collection->remove();
        foreach ($rows as $row) {
            $collection->insert([
                'id_invoice' => $row['id_invoice'],
                'id_account' => (int)$row['id_account'],
                'coupon' => $row['coupon'],
                'subtotal' => (float)$row['subtotal'],
                'shipping' => (float)$row['shipping'],
                'tax' => (float)$row['tax'],
                'total' => (float)$row['total'],
                'currency' => $row['currency'],
                'payment_method' => $row['payment_method'],
                'payment_date' => new UTCDateTime($row['payment_date']*1000),
                'transaction' => $row['transaction'],
                'info' => $row['info'],
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at']*1000),
                'updated_at' => new UTCDateTime($row['updated_at']*1000),
            ]);
        }
        echo "Invoice migration completed.\n";
    }

    /**
     * Copy Item to MongoDB
     */
    protected function item()
    {
        echo "Migrating Item...\n";
        $rows = (new Query())->select('*')->from('{{%billing_item}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('billing_item');
        $collection->remove();
        foreach ($rows as $row) {
            $collection->insert([
                'id_invoice' => $row['id_invoice'],
                'name' => $row['name'],
                'quantity' => (int)$row['quantity'],
                'price' => (float)$row['price'],
                'details' => $row['details'],
            ]);
        }
        echo "Item migration completed.\n";
    }


    /**
     * copy Info to MongoDB
     */
    protected function info()
    {
        echo "Migrating Info...\n";
        $rows = (new Query())->select('*')->from('{{%billing_info}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('billing_info');
        $collection->remove();
        foreach ($rows as $row) {
            $collection->insert([
                'id_account' => (int)$row['id_account'],
                'company' => $row['company'],
                'tax_id' => $row['tax_id'],
                'f_name' => $row['f_name'],
                'l_name' => $row['l_name'],
                'address' => $row['address'],
                'address2' => $row['address2'],
                'city' => $row['city'],
                'state' => $row['state'],
                'zip' => $row['zip'],
                'country' => $row['country'],
                'phone' => $row['phone'],
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
            ]);
        }
        echo "Info migration completed.\n";
    }

    /**
     * copy Coupon to MongoDB
     */
    protected function coupon()
    {
        echo "Migrating Coupon Address...\n";
        $rows = (new Query())->select('*')->from('{{%billing_coupon}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('billing_coupon');
        $collection->remove();
        foreach ($rows as $row) {
            $collection->insert([
                'code' => $row['code'],
                'currency' => $row['currency'],
                'discount' => (float)$row['discount'],
                'discount_type' => $row['discount_type'],
                'begin_at' => new UTCDateTime($row['begin_at'] * 1000),
                'end_at' => new UTCDateTime($row['end_at'] * 1000),
                'quantity' => (int)$row['quantity'],
                'reuse' => $row['reuse'],
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
            ]);
        }
        echo "Coupon migration completed.\n";
    }

    /**
     * copy btc to mongodb
     */
    protected function btc()
    {
        echo "Migrating BTC Address...\n";
        $rows = (new Query())->select('*')->from('{{%billing_bitcoin_payments}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('billing_bitcoin_payments');
        $collection->remove();
        foreach ($rows as $row) {
            $collection->insert([
                'address' => $row['address'],
                'id_invoice' => $row['id_invoice'],
                'id_account' => $row['id_account'],
                'request_balance' => (float)$row['request_balance'],
                'total_received ' => (float)$row['total_received'],
                'final_balance' => (float)$row['final_balance'],
                'tx_id' => $row['tx_id'],
                'tx_date' => new UTCDateTime($row['tx_date'] * 1000),
                'tx_confirmed' => (int)$row['tx_confirmed'],
                'tx_check_date' => new UTCDateTime($row['tx_check_date'] * 1000),
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
            ]);
        }
        echo "BTC Address migration completed.\n";
    }

    /**
     * copy bank to mongodb
     */
    protected function bank()
    {
        echo "Migrating Bank info...\n";
        $rows = (new Query())->select('*')->from('{{%billing_bank}}')->all();
        $collection = \Yii::$app->mongodb->getCollection('billing_bank');
        $collection->remove();
        foreach ($rows as $row) {
            $collection->insert([
                'country' => $row['country'],
                'title' => $row['title'],
                'info' => $row['info'],
                'currency' => $row['currency'],
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
            ]);
        }
        echo "Bank info migration completed.\n";
    }
}