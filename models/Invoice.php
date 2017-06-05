<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace modernkernel\billing\models;

use common\models\Account;
use common\models\Setting;
use modernkernel\billing\components\CurrencyLayer;
use modernkernel\billing\components\Tax;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%billing_invoice}}".
 *
 * @property string $id
 * @property integer $id_account
 * @property string $coupon
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

    const STATUS_PAID_UNCONFIRMED = 50;

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
            self::STATUS_PAID_UNCONFIRMED => Yii::$app->getModule('billing')->t('Receiving'),

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
            'coupon' => Yii::$app->getModule('billing')->t('Coupon'),
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
        /* common */
        if (empty($this->id_account)) {
            $this->id_account = Yii::$app->user->id;
        }

        /* billing info */
        $info = BillingInfo::getInfo($this->id_account);
        $this->info = json_encode($info);

        if ($insert) {
            $this->id = strtoupper(uniqid());
        } else {
            $this->calculate();
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
        $info = json_decode($this->info, true);
        if (!empty($info['tax_id']) && !empty($info['country'])) {
            $tax = new Tax();
            $this->tax = $tax->getTaxValue($info['country']);
        }
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
        if ($status == self::STATUS_PAID_UNCONFIRMED) {
            return '<span class="label label-info">' . $this->statusText . '</span>';
        }
        return $this->statusText;
    }

    /**
     * convert invoice currency
     * @param $currency
     * @return bool
     */
    public function convertCurrencyTo($currency)
    {
        $fromCurrency = $this->currency;
        if ($fromCurrency == $currency) {
            return true;
        }


        $cl = new CurrencyLayer();
        if (!empty($cl->quotes)) {
            $this->shipping = $cl->convert($fromCurrency, $currency, $this->shipping);
            $this->tax = $cl->convert($fromCurrency, $currency, $this->tax);
            $this->currency = $currency;
            if ($this->save()) {
                /* item */
                foreach ($this->items as $i => $item) {
                    $item->price = $cl->convert($fromCurrency, $currency, $item->price);
                    if (!$item->save()) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;

    }

    /**
     * get invoice url
     * @param bool $absolute
     */
    public function getInvoiceUrl($absolute = false)
    {
        $act = 'createUrl';
        if ($absolute) {
            $act = 'createAbsoluteUrl';
        }
        return Yii::$app->urlManagerFrontend->$act(['/billing/invoice/show', 'id' => $this->id]);
    }

    /**
     * sending new invoice email
     * @return bool
     */
    public function sendMail()
    {
        $subject = Yii::$app->getModule('billing')->t('{APP}: You\'ve got a new invoice', ['APP' => Yii::$app->name]);
        return Yii::$app->mailer
            ->compose(
                [
                    'html' => 'new-invoice-html',
                    'text' => 'new-invoice-text'
                ],
                ['title' => $subject, 'model' => $this]
            )
            ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
            ->setTo($this->account->email)
            ->setSubject($subject)
            ->send();
    }

    /**
     * @return array
     */
    public static function getPaymentMethodOption()
    {
        $option = [
            'Paypal' => Yii::$app->getModule('billing')->t('Paypal'),
            'Credit/Debit Card' => Yii::$app->getModule('billing')->t('Credit/Debit Card'),
            'Bank Wire' => Yii::$app->getModule('billing')->t('Bank Wire'),
            'Cash' => Yii::$app->getModule('billing')->t('Cash'),
        ];
        return $option;
    }


    /**
     *
     * @return array
     */
    public function getBankInfo()
    {
        $info = $this->loadInfo();
        $bankInfo = [];
        if (!empty($info['country'])) {
            $banks = Bank::find()->where(['country' => $info['country'], 'status' => Bank::STATUS_ACTIVE])->all();
            foreach ($banks as $bank) {
                $bankInfo[] = [
                    'info' => $bank->info,
                    'currency' => $bank->currency,
                    'total' => (new CurrencyLayer())->convert($this->currency, $bank->currency, $this->total)
                ];
            }
        }


        return $bankInfo;
    }

    /**
     * apply coupon
     * @param $code
     * @return bool
     */
    public function applyCoupon($code){
        $coupon=Coupon::findOne($code);
        if($coupon){
            /* percent */
            if($coupon->discount_type==Coupon::DISCOUNT_TYPE_PERCENT){
                $discount=new Item();
                $discount->id_invoice=$this->id;
                $discount->name=Yii::$app->getModule('billing')->t('Coupon {CODE}', ['CODE'=>$coupon->code]);
                $discount->quantity=1;
                $discount->price=($this->subtotal*$coupon->discount/100)*-1;
                $discount->save();
            }
            /* value */
            if($coupon->discount_type==Coupon::DISCOUNT_TYPE_VALUE){
                /* value > invoice total */
                $value=$coupon->discount;
                if($value>$this->subtotal){
                    $value=$this->subtotal;
                }
                $discount=new Item();
                $discount->id_invoice=$this->id;
                $discount->name=Yii::$app->getModule('billing')->t('Coupon {CODE}', ['CODE'=>$coupon->code]);
                $discount->quantity=1;
                $discount->price=$value*-1;
                $discount->save();
            }

            /* save */
            $this->coupon=$code;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * cancel invoice
     * @return bool
     */
    public function cancel(){
        $this->status=Invoice::STATUS_CANCELED;
        if($this->save()){
            /* send email */
            $subject = Yii::$app->getModule('billing')->t('{APP}: Your invoice has been canceled', ['APP' => Yii::$app->name]);
            Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'cancel-invoice-html',
                        'text' => 'cancel-invoice-text'
                    ],
                    ['title' => $subject, 'model' => $this]
                )
                ->setFrom([Setting::getValue('outgoingMail') => Yii::$app->name])
                ->setTo($this->account->email)
                ->setSubject($subject)
                ->send();
            return true;
        }
        return false;
    }
}
