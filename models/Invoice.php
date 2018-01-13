<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace powerkernel\billing\models;

use common\components\CurrencyFraction;
use common\models\Account;
use powerkernel\billing\components\CurrencyLayer;
use powerkernel\billing\components\Tax;
use Yii;

/**
 * This is the model class for Invoice
 *
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property string $id_invoice
 * @property integer|string $id_account
 * @property string $coupon
 * @property double $subtotal
 * @property double $shipping
 * @property double $tax
 * @property double $total
 * @property string $currency
 * @property string $payment_method
 * @property integer|\MongoDB\BSON\UTCDateTime $payment_date
 * @property string $transaction
 * @property string $info
 * @property string $shipping_info
 * @property string $note
 * @property string $status
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 *
 * @property Account $account
 * @property Item[] $items
 */
class Invoice extends InvoiceBase
{
    const STATUS_PENDING = 'STATUS_PENDING'; //10;
    const STATUS_PAID = 'STATUS_PAID'; //20;

    const STATUS_CANCELED = 'STATUS_CANCELED'; //30
    const STATUS_REFUNDED = 'STATUS_REFUNDED'; //40;

    const STATUS_PAID_UNCONFIRMED = 'STATUS_PAID_UNCONFIRMED'; //50;

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
    public function rules()
    {
        /* date */
        if (is_a($this, 'yii\mongodb\ActiveRecord')) {
            $date = [
                [['created_at', 'updated_at', 'payment_date'], 'yii\mongodb\validators\MongoDateValidator'],
                //[['payment_date'], 'yii\mongodb\validators\MongoDateValidator', 'format' => 'MM/dd/yyyy', 'mongoDateAttribute'=>'payment_date'],
            ];
        } else {
            $date = [
                [['payment_date', 'created_at', 'updated_at'], 'integer'],
            ];
        }

        $default = [
            [['shipping', 'tax'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['currency'], 'default', 'value'=>'USD'],

            [['subtotal', 'shipping', 'tax', 'total'], 'number', 'min' => 0],
            [['id_invoice'], 'string', 'max' => 23],
            [['currency'], 'string', 'max' => 3],
            [['payment_method', 'transaction'], 'string', 'max' => 50],
            [['info', 'shipping_info', 'note'], 'string'],

            [['id_account'], 'safe'],
            [['id_account'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['id_account' => Yii::$app->params['mongodb']['account'] ? '_id' : 'id']],

            //['payment_date_picker', 'string']
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
            'id_invoice' => Yii::$app->getModule('billing')->t('ID'),
            'id_account' => Yii::$app->getModule('billing')->t('Account'),
            'coupon' => Yii::$app->getModule('billing')->t('Coupon'),
            'subtotal' => Yii::$app->getModule('billing')->t('Subtotal'),
            'shipping' => Yii::$app->getModule('billing')->t('Shipping'),
            'tax' => Yii::$app->getModule('billing')->t('Tax'),
            'total' => Yii::$app->getModule('billing')->t('Total'),
            'currency' => Yii::$app->getModule('billing')->t('Currency'),
            'payment_method' => Yii::$app->getModule('billing')->t('Payment Method'),
            'payment_date' => Yii::$app->getModule('billing')->t('Payment Date'),
            'payment_date_picker' => Yii::$app->getModule('billing')->t('Payment Date'),
            'transaction' => Yii::$app->getModule('billing')->t('Transaction'),
            'info' => Yii::$app->getModule('billing')->t('Billing Information'),
            'shipping_info'=> Yii::$app->getModule('billing')->t('Shipping Information'),
            'note' => Yii::$app->getModule('billing')->t('Note'),
            'status' => Yii::$app->getModule('billing')->t('Status'),
            'created_at' => Yii::$app->getModule('billing')->t('Date'),
            'updated_at' => Yii::$app->getModule('billing')->t('Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAccount()
    {
        if (Yii::$app->params['mongodb']['account']) {
            return $this->hasOne(Account::className(), ['_id' => 'id_account']);
        } else {
            return $this->hasOne(Account::className(), ['id' => 'id_account']);
        }
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['id_invoice' => 'id_invoice']);
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

        /* shipping */
        $this->shipping = (float)$this->shipping;
        $this->tax = (float)$this->tax;

        if ($insert) {
            $this->id_invoice = strtoupper(uniqid());
        } else {
            $this->calculate();
            if ($this->status == self::STATUS_PAID && empty($this->payment_date)) {
                $this->touch('payment_date');
            }
        }

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
//        if ($insert) {
//            $this->sendMail();
//        }
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
            $this->tax = round($this->subtotal * $this->tax, CurrencyFraction::getFraction($this->currency));
        }

        /* total */
        $this->total = round($this->subtotal + $this->shipping + $this->tax, CurrencyFraction::getFraction($this->currency));

        if ($this->total == 0) {
            $this->status = Invoice::STATUS_PAID;
        }
    }

    /**
     * all items total amount
     */
    protected function getTotalItemAmount()
    {
        $total = 0.0;
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
     * @return string url
     */
    public function getInvoiceUrl($absolute = false)
    {
        $act = 'createUrl';
        if ($absolute) {
            $act = 'createAbsoluteUrl';
        }
        if (Yii::$app->id != 'app-frontend') {
            return Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/billing/invoice/show', 'id' => (string)$this->id]);
        }
        return Yii::$app->urlManager->$act(['/billing/invoice/show', 'id' => (string)$this->id]);
    }

    /**
     * admin invoice url
     * @return mixed
     */
    public function getAdminInvoiceUrl()
    {
        return Yii::$app->urlManagerBackend->createAbsoluteUrl(['billing/invoice/view', 'id' => (string)$this->id]);
    }

    /**
     * sending new invoice email to admin and user
     * @return bool
     */
    public function sendMail()
    {
        $this->calculate();
        return $this->sendEmailAdmin() && $this->sendEmailUser();
    }

    /**
     * send email to admin
     * @return bool
     */
    protected function sendEmailAdmin()
    {
        $subject = Yii::$app->getModule('billing')->t('{APP}: New invoice #{ID} placed', ['ID' => $this->id_invoice, 'APP' => Yii::$app->name]);
        return Yii::$app->mailer
            ->compose(
                [
                    'html' => 'new-invoice-admin-html',
                    'text' => 'new-invoice-admin-text'
                ],
                ['title' => $subject, 'model' => $this]
            )
            ->setFrom([\common\models\Setting::getValue('outgoingMail') => Yii::$app->name])
            ->setTo(\common\models\Setting::getValue('adminMail'))
            ->setSubject($subject)
            ->send();
    }

    /**
     * send email to user
     * @return bool
     */
    protected function sendEmailUser()
    {
        $subject = Yii::$app->getModule('billing')->t('{APP}: you\'ve got a new invoice #{ID}', ['ID' => $this->id_invoice, 'APP' => Yii::$app->name]);
        return Yii::$app->mailer
            ->compose(
                [
                    'html' => 'new-invoice-user-html',
                    'text' => 'new-invoice-user-text'
                ],
                ['title' => $subject, 'model' => $this]
            )
            ->setFrom([\common\models\Setting::getValue('outgoingMail') => Yii::$app->name])
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
                    'total' => $bank->currency!=$this->currency?(new CurrencyLayer())->convert($this->currency, $bank->currency, $this->total):$this->total
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
    public function applyCoupon($code)
    {
        $coupon = Coupon::find()->where(['code' => $code])->one();
        if ($coupon) {
            /* percent */
            if ($coupon->discount_type == Coupon::DISCOUNT_TYPE_PERCENT) {
                $discount = new Item();
                $discount->id_invoice = $this->id_invoice;
                $discount->name = Yii::$app->getModule('billing')->t('Coupon {CODE}', ['CODE' => $coupon->code]);
                $discount->quantity = 1;
                $discount->price = ($this->subtotal * $coupon->discount / 100) * -1;
                $discount->save();
            }
            /* value */
            if ($coupon->discount_type == Coupon::DISCOUNT_TYPE_VALUE) {
                /* value > invoice total */
                $value = $coupon->discount;
                if ($value > $this->subtotal) {
                    $value = $this->subtotal;
                }
                $discount = new Item();
                $discount->id_invoice = $this->id_invoice;
                $discount->name = Yii::$app->getModule('billing')->t('Coupon {CODE}', ['CODE' => $coupon->code]);
                $discount->quantity = 1;
                $discount->price = $value * -1;
                $discount->save();
            }

            /* save */
            $this->coupon = $code;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * cancel invoice
     * @return bool
     */
    public function cancel()
    {
        $this->status = Invoice::STATUS_CANCELED;
        if ($this->save()) {
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
                ->setFrom([\common\models\Setting::getValue('outgoingMail') => Yii::$app->name])
                ->setTo($this->account->email)
                ->setSubject($subject)
                ->send();
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $items = $this->getItems();
        foreach ($items as $item) {
            $item->delete();
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }
}
