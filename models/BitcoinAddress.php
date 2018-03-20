<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace powerkernel\billing\models;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKeyFactory;
use Yii;
use yii\httpclient\Client;

/**
 * This is the model class for BitcoinAddress
 *
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property string $address
 * @property string $id_invoice
 * @property integer|\MongoDB\BSON\ObjectID|string $id_account
 * @property string $request_balance
 * @property string $total_received
 * @property string $final_balance
 * @property string $tx_id
 * @property integer $tx_date
 * @property integer $tx_confirmed
 * @property integer $tx_check_date
 * @property integer $status
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 *
 * @property Invoice $invoice
 */
class BitcoinAddress extends BitcoinAddressBase
{


    const STATUS_NEW = 'STATUS_NEW'; //10
    const STATUS_USED = 'STATUS_USED'; //20
    const STATUS_DONE = 'STATUS_DONE'; //30
    const STATUS_UNCONFIRMED = 'STATUS_UNCONFIRMED'; //40


    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_NEW => Yii::$app->getModule('billing')->t('New'),
            self::STATUS_USED => Yii::$app->getModule('billing')->t('Used'),
            self::STATUS_DONE => Yii::$app->getModule('billing')->t('Done'),
            self::STATUS_UNCONFIRMED => Yii::$app->getModule('billing')->t('Unconfirmed'),
        ];
        if (is_array($e))
            foreach ($e as $i)
                unset($option[$i]);
        return $option;
    }

    /**
     * get status text
     * @return string
     */
    public function getStatusText()
    {
        $status = $this->status;
        $list = self::getStatusOption();
        if (!empty($status) && in_array($status, array_keys($list))) {
            return $list[$status];
        }
        return Yii::$app->getModule('billing')->t('Unknown');
    }

    /**
     * get status color text
     * @return string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        $list = self::getStatusOption();

        $color = 'default';
        if ($status == self::STATUS_NEW) {
            $color = 'info';
        }
        if ($status == self::STATUS_USED) {
            $color = 'default';
        }
        if ($status == self::STATUS_DONE) {
            $color = 'primary';
        }
        if ($status == self::STATUS_UNCONFIRMED) {
            $color = 'warning';
        }

        if (!empty($status) && in_array($status, array_keys($list))) {
            return '<span class="label label-' . $color . '">' . $list[$status] . '</span>';
        }
        return '<span class="label label-' . $color . '">' . Yii::$app->getModule('billing')->t('Unknown') . '</span>';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        if (is_a($this, '\yii\mongodb\ActiveRecord')) {
            $date = [
                [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator']
            ];
        } else {
            $date = [
                [['created_at', 'updated_at'], 'integer']
            ];
        }
        $default = [
            [['request_balance', 'total_received', 'final_balance'], 'default', 'value' => 0.0],
            [['status'], 'default', 'value' => self::STATUS_NEW],

            [['address'], 'required'],
            [['request_balance', 'total_received', 'final_balance'], 'number'],
            [['address'], 'string', 'max' => 35],
            [['id_invoice'], 'string', 'max' => 32],
            [['tx_id'], 'string', 'max' => 64],
        ];
        return array_merge($default, $date);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::$app->getModule('billing')->t('ID'),
            'address' => Yii::$app->getModule('billing')->t('Address'),
            'id_invoice' => Yii::$app->getModule('billing')->t('Invoice'),
            'id_account' => Yii::$app->getModule('billing')->t('Account'),
            'request_balance' => Yii::$app->getModule('billing')->t('Request Balance'),
            'total_received' => Yii::$app->getModule('billing')->t('Total Received'),
            'final_balance' => Yii::$app->getModule('billing')->t('Final Balance'),
            'tx_id' => Yii::$app->getModule('billing')->t('TX ID'),
            'tx_date' => Yii::$app->getModule('billing')->t('TX Date'),
            'tx_confirmed' => Yii::$app->getModule('billing')->t('TX Confirmed'),
            'tx_check_date' => Yii::$app->getModule('billing')->t('TX Check Date'),
            'status' => Yii::$app->getModule('billing')->t('Status'),
            'created_at' => Yii::$app->getModule('billing')->t('Created At'),
            'updated_at' => Yii::$app->getModule('billing')->t('Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::class, ['id_invoice' => 'id_invoice']);
    }

    /**
     * generate btc address
     */
    public static function generate()
    {
        $xpub = \powerkernel\billing\models\Setting::getValue('btcWalletXPub');
        if (!empty($xpub)) {
            $network = Bitcoin::getNetwork();
            $hk = HierarchicalKeyFactory::fromExtended($xpub, $network);
            /* count total new address*/
            $new = BitcoinAddress::find()->where(['status' => self::STATUS_NEW])->count();
            /* only generate more address if < 20*/
            if ($new < 20) {
                $child = BitcoinAddress::find()->count();
                $n = 20 - $new;
                for ($i = 0; $i < $n; $i++) {
                    $address = $hk->deriveChild($child + $i)->getPublicKey()->getAddress();
                    $addr = new BitcoinAddress();
                    $addr->address = $address->getAddress();
                    $addr->save();
                }
            }

        }
    }


    /**
     * get address transactions (only tx which fund is received)
     * @return null|[]
     */
    public function getTx()
    {
        $client = new Client(['baseUrl' => 'https://blockchain.info']);
        $response = $client->get('rawaddr/' . $this->address)->send();
        if ($response->statusCode == '200') {
            $json = $response->getContent();
            $info = json_decode($json, true);
            if (isset($info['n_tx']) && $info['n_tx'] > 0) {
                foreach ($info['txs'] as $tx) {
                    /* check outs */
                    foreach ($tx['out'] as $output) {
                        if ($output['addr'] == $this->address && ($output['value'] / 100000000) == $this->request_balance) {
                            /* get tx info/ check double spend */
                            return $this->verifyTx($tx['hash']);
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
     * check double spend, return tx info
     * @param $hash string
     * @return bool|[]
     */
    public function verifyTx($hash){
        $client = new Client(['baseUrl' => 'https://blockchain.info']);
        $response = $client->get('rawtx/' . $hash)->send();
        if ($response->statusCode == '200') {
            $json = $response->getContent();
            $tx = json_decode($json, true);
            if($tx['double_spend']==false){
                return $tx;
            }
            return false;
        }
        return false;
    }

    /**
     * get tx confirm number
     * @param $tx []
     * @return int
     */
    public function getTxConfirmation($tx)
    {
        if (isset($tx['block_height'])) {
            $client = new Client(['baseUrl' => 'https://blockchain.info']);
            $response = $client->get('q/getblockcount/')->send();
            if ($response->statusCode == '200') {
                $blockCount = (int)$response->getContent();
                return $blockCount - $tx['block_height'];
            }
            return 0;
        }
        return 0;
    }


    /**
     * check payment
     * @return string
     */
    public function checkPayment()
    {
        $tx=$this->getTx();
        if(is_array($tx)){ // found tx with no double spend
            /* check balance */
            $client = new Client(['baseUrl' => 'https://blockchain.info']);
            $response = $client->get('rawaddr/' . $this->address)->send();
            if ($response->statusCode == '200') {
                $json = $response->getContent();
                $info=json_decode($json, true);
                $this->total_received = $info['total_received'] == 0 ? 0 : $info['total_received'] / 100000000;
                $this->final_balance = $info['final_balance'] == 0 ? 0 : $info['final_balance'] / 100000000;
            }

            /* update addr info */
            $confirmations=$this->getTxConfirmation($tx);
            $this->tx_id = $tx['hash'];
            $this->touch('tx_check_date');
            $this->tx_date = $tx['time'];
            $this->tx_confirmed = (int)$confirmations;
            $this->status = BitcoinAddress::STATUS_UNCONFIRMED;
            if ($confirmations > 2) {
                $this->status = BitcoinAddress::STATUS_DONE;
            }
            $this->save();
            return json_encode(['payment_received' => true]);
        }
        return json_encode(['payment_received' => false]);
    }


    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->request_balance = round($this->request_balance, 8);
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        if (!empty($this->tx_id)) {
            /* paid but unconfirmed tx */
            if ($this->tx_confirmed < 3) {
                $this->invoice->status = Invoice::STATUS_PAID_UNCONFIRMED;
            } else {
                $this->invoice->status = Invoice::STATUS_PAID;
            }
            $this->invoice->payment_method = 'Bitcoin';
            $this->invoice->save();
        }
    }

    /**
     * release address
     */
    public function release()
    {
        $this->id_invoice = null;
        $this->id_account = null;
        $this->request_balance = 0.00000000;
        $this->total_received = 0.00000000;
        $this->final_balance = 0.00000000;

        $this->tx_id = null;
        $this->tx_confirmed = 0;
        $this->tx_date = null;
        $this->tx_check_date = null;
        $this->status = self::STATUS_NEW;
        $this->save();
    }
}
