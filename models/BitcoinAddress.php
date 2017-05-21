<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace modernkernel\billing\models;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKeyFactory;
use common\models\Setting;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\httpclient\Client;

/**
 * This is the model class for table "{{%billing_bitcoin_payments}}".
 *
 * @property integer $id
 * @property string $address
 * @property string $id_invoice
 * @property integer $id_account
 * @property string $request_balance
 * @property string $total_received
 * @property string $final_balance
 * @property string $tx_id
 * @property integer $tx_date
 * @property integer $tx_confirmed
 * @property integer $tx_check_date
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Invoice $invoice
 */
class BitcoinAddress extends ActiveRecord
{


    const STATUS_NEW = 10;
    const STATUS_USED = 20;
    const STATUS_DONE = 30;
    const STATUS_UNCONFIRMED = 40;


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
        return '<span class="label label-' . $color . '">' . Yii::$app->getModule('ticket')->t('Unknown') . '</span>';
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_bitcoin_payments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address'], 'required'],
            [['id', 'id_account', 'tx_date', 'tx_confirmed', 'tx_check_date', 'status', 'created_at', 'updated_at'], 'integer'],
            [['request_balance', 'total_received', 'final_balance'], 'number'],
            [['address'], 'string', 'max' => 35],
            [['id_invoice'], 'string', 'max' => 23],
            [['tx_id'], 'string', 'max' => 64],
        ];
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'id_invoice']);
    }

    /**
     * generate btc address
     */
    public static function generate()
    {
        $xpub = Setting::getValue('btcWalletXPub');
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
     * check payment
     * @return string|null
     */
    public function checkPayment()
    {
        $addr = $this->address;
        $btc = $this->request_balance;

        $client = new Client(['baseUrl' => 'https://blockexplorer.com/api']);
        $response = $client->get('txs', ['address' => $addr])->send();

        $r = $response->getContent();
        $tx = json_decode($r, true);

        $txid = null;
        $txConfirmations = null;
        $txDate = null;
        $found = false;

        if (!empty($tx['txs'])) {
            foreach ($tx['txs'] as $transaction) {
                $txid = $transaction['txid'];
                $txConfirmations = $transaction['confirmations'];
                $txDate = $transaction['time'];
                foreach ($transaction['vout'] as $out) {
                    if ($out['value'] == $btc) {
                        if (in_array($addr, $out['scriptPubKey']['addresses'])) {
                            $found = true;
                            break;
                        }
                    }
                }
            }

        }

        /* payment received? */
        if (!empty($txid) && $found) {
            /* check balance */
            $client = new Client(['baseUrl' => 'https://blockexplorer.com/api/']);
            $balance['totalReceived'] = $client->get('addr/' . $this->address . '/totalReceived')->send()->getContent();
            $balance['balance'] = $client->get('addr/' . $this->address . '/balance')->send()->getContent();
            $this->total_received = $balance['totalReceived'] == 0 ? 0 : $balance['totalReceived'] / 100000000;
            $this->final_balance = $balance['balance'] == 0 ? 0 : $balance['balance'] / 100000000;

            $this->tx_id = $txid;
            $this->tx_check_date = time();
            $this->tx_date = empty($this->tx_date)?$txDate:$this->tx_date;
            $this->tx_confirmed = $txConfirmations;
            $this->status=BitcoinAddress::STATUS_UNCONFIRMED;
            if($txConfirmations>2){
                $this->status=BitcoinAddress::STATUS_DONE;
            }
            $this->save();
            return json_encode(['payment_received' => true]);
        }

        return json_encode(['payment_received' => false]);

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
