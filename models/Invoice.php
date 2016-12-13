<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace modernkernel\billing\models;

use common\models\Account;
use common\models\Setting;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\httpclient\Client;

/**
 * This is the model class for table "{{%billing_invoice}}".
 *
 * @property string $id
 * @property integer $id_account
 * @property double $subtotal
 * @property double $shipping
 * @property double $tax
 * @property double $total
 * @property string $currency
 *
 * @property string $payment_method
 * @property integer $payment_date
 * @property string $transaction
 * @property string $info
 *
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Account $account
 * @property Item[] $items
 */
class Invoice extends ActiveRecord
{


    const STATUS_PENDING = 10;
    const STATUS_PAID = 20;
    const STATUS_CANCELED = 30;
    const STATUS_REFUNDED = 40;

    public $payment_date_picker;

    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_PENDING => Yii::$app->getModule('billing')->t('Pending'),
            self::STATUS_PAID => Yii::$app->getModule('billing')->t('Paid'),
            self::STATUS_CANCELED => Yii::$app->getModule('billing')->t('Canceled'),
            self::STATUS_REFUNDED => Yii::$app->getModule('billing')->t('Refunded'),
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id', 'created_at', 'updated_at'], 'required'],
            [['id_account', 'payment_date', 'status', 'created_at', 'updated_at'], 'integer'],
            [['subtotal', 'shipping', 'tax', 'total'], 'number', 'min' => 0],
            [['id'], 'string', 'max' => 23],
            [['currency'], 'string', 'max' => 3],
            [['payment_method', 'transaction'], 'string', 'max' => 50],
            [['info'], 'string'],
            [['id_account'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['id_account' => 'id']],

            ['payment_date_picker', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::$app->getModule('billing')->t('ID'),
            'id_account' => Yii::$app->getModule('billing')->t('Account'),
            'subtotal' => Yii::$app->getModule('billing')->t('Subtotal'),
            'shipping' => Yii::$app->getModule('billing')->t('Shipping'),
            'tax' => Yii::$app->getModule('billing')->t('Tax'),
            'total' => Yii::$app->getModule('billing')->t('Total'),
            'currency' => Yii::$app->getModule('billing')->t('Currency'),
            'payment_method' => Yii::$app->getModule('billing')->t('Payment Method'),
            'payment_date' => Yii::$app->getModule('billing')->t('Payment Date'),
            'transaction' => Yii::$app->getModule('billing')->t('Transaction'),
            'info' => Yii::$app->getModule('billing')->t('Billing Information'),
            'status' => Yii::$app->getModule('billing')->t('Status'),
            'created_at' => Yii::$app->getModule('billing')->t('Date'),
            'updated_at' => Yii::$app->getModule('billing')->t('Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'id_account']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['id_invoice' => 'id']);
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
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->id = strtoupper(uniqid());
        } else {
            $this->calculate();
        }

        /* common */
        if (empty($this->id_account)) {
            $this->id_account = Yii::$app->user->id;
            /* billing info */
            $info = BillingInfo::getInfo($this->id_account);
            $this->info = json_encode($info);
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * calculate money
     */
    public function calculate()
    {
        $this->subtotal = $this->getTotalItemAmount();

        /* tax */
        if ($this->tax < 1) {
            $this->tax = $this->subtotal * $this->tax;
        }

        /* total */
        $this->total = $this->subtotal + $this->shipping + $this->tax;
        if ($this->total == 0) {
            $this->status = Invoice::STATUS_PAID;
        }
    }

    /**
     * all items total amount
     */
    protected function getTotalItemAmount()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->quantity * $item->price;
        }
        return $total;
    }

    /**
     * load billing info
     * @return array|mixed
     */
    public function loadInfo()
    {
        if (empty($this->info)) {
            $info = BillingInfo::getInfo($this->id_account);
            $this->info = json_encode($info);
            $this->save();
        } else {
            $info = json_decode($this->info, true);
        }
        return $info;
    }

    /**
     * status color text
     * @return string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        if ($status == self::STATUS_PAID) {
            return '<span class="label label-success">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_PENDING) {
            return '<span class="label label-default">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_REFUNDED) {
            return '<span class="label label-danger">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_CANCELED) {
            return '<span class="label label-warning">' . $this->statusText . '</span>';
        }
        return $this->statusText;
    }

    /**
     * convert currency. Note: free api only support convert to USD
     * @param $currency
     * @return bool
     */
    public function convertCurrencyTo($currency)
    {
        $app=Setting::getValue('openExchangeRatesApp');
        if ($this->currency != $currency && !empty($app)) {
            $fromCurrency=$this->currency;

            $rateUrl = 'https://openexchangerates.org/api/latest.json';
            $client = new Client();

            /* cache, save API */
            $response = Yii::$app->cache->get('openexchangerates');
            if ($response === false) {
                $response = $client->createRequest()
                    ->setMethod('get')
                    ->setUrl($rateUrl)
                    ->setData(['app_id' => $app, 'base'=>$currency])
                    ->send();
                Yii::$app->cache->set('openexchangerates', $response, 43200); // 12 hours
            }


            if ($response->isOk && !empty($response->data['rates'])) {
                $rate = $response->data['rates'][$fromCurrency];
                if (!empty($rate)) {
                    $this->currency = $currency;
                    //$this->subtotal=$this->subtotal/$rate;
                    $this->shipping = $this->shipping == 0 ? 0 : $this->shipping / $rate;
                    $this->tax = $this->tax == 0 ? 0 : $this->tax / $rate;
                    //$this->total=$this->total/$rate;
                    if ($this->save()) {
                        /* item */
                        foreach ($this->items as $i => $item) {
                            $item->price = $item->price == 0 ? 0 : $item->price / $rate;
                            if (!$item->save()) {
                                return false;
                            }
                        }
                        return true;
                    }

                    return false;
                }

                return false;
            }
            return false;
        }
        return false;
    }
}
