<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace powerkernel\billing\models;

use Yii;

/**
 * This is the model class for Coupon.
 *
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property string $code
 * @property string $currency
 * @property float $discount
 * @property string $discount_type
 * @property integer|\MongoDB\BSON\UTCDateTime $begin_at
 * @property integer|\MongoDB\BSON\UTCDateTime $end_at
 * @property integer $quantity
 * @property integer $reuse
 * @property string $status
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 */
class Coupon extends CouponBase
{
    const STATUS_ACTIVE = 'STATUS_ACTIVE'; // 10;
    const STATUS_INACTIVE = 'STATUS_INACTIVE'; // 20;

    const DISCOUNT_TYPE_PERCENT = 'DISCOUNT_TYPE_PERCENT'; // 10;
    const DISCOUNT_TYPE_VALUE = 'DISCOUNT_TYPE_VALUE'; // 20

    public $begin_date_picker;
    public $end_date_picker;


    /**
     * get discount options
     * @param null $e
     * @return array
     */
    public static function getDiscountOption($e = null)
    {
        $option = [
            self::DISCOUNT_TYPE_PERCENT => Yii::$app->getModule('billing')->t('Percent'),
            self::DISCOUNT_TYPE_VALUE => Yii::$app->getModule('billing')->t('Value'),
        ];
        if (is_array($e))
            foreach ($e as $i)
                unset($option[$i]);
        return $option;
    }

    /**
     * get discount text
     * @return mixed
     */
    public function getDiscountText()
    {
        $type = $this->discount_type;
        $list = self::getDiscountOption();
        if (!empty($type) && in_array($type, array_keys($list))) {
            return $list[$type];
        }
        return Yii::$app->getModule('billing')->t('Unknown');
    }

    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_ACTIVE => Yii::$app->getModule('billing')->t('Active'),
            self::STATUS_INACTIVE => Yii::$app->getModule('billing')->t('Inactive'),
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
        if ($status == self::STATUS_ACTIVE) {
            $color = 'primary';
        }
        if ($status == self::STATUS_INACTIVE) {
            $color = 'danger';
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
                [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator'],
                [['begin_at'], 'yii\mongodb\validators\MongoDateValidator', 'format' => 'MM/dd/yyyy', 'mongoDateAttribute'=>'begin_at'],
                [['end_at'], 'yii\mongodb\validators\MongoDateValidator', 'format' => 'MM/dd/yyyy', 'mongoDateAttribute'=>'end_at']
            ];
        }
        else {
            $date = [
                [['created_at', 'updated_at'], 'integer'],
                [['begin_at', 'end_at'], 'date', 'format' => 'MM/dd/yyyy']
            ];
        }
        $default = [
            [['code', 'currency', 'discount', 'discount_type', 'begin_at', 'end_at', 'quantity', 'reuse', 'status'], 'required'],
            [['code'], 'unique'],
            //[['discount'], 'number'],
            [['quantity', 'reuse'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['begin_date_picker', 'end_date_picker'], 'required', 'on' => ['create']],
            [['end_at'], 'compare', 'compareAttribute' => 'begin_at', 'operator' => '>=']
        ];
        return array_merge($default, $date);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::$app->getModule('billing')->t('Code'),
            'currency' => Yii::$app->getModule('billing')->t('Currency'),
            'discount' => Yii::$app->getModule('billing')->t('Discount'),
            'discount_type' => Yii::$app->getModule('billing')->t('Discount Type'),
            'begin_at' => Yii::$app->getModule('billing')->t('Begin Date'),
            'end_at' => Yii::$app->getModule('billing')->t('End Date'),
            'quantity' => Yii::$app->getModule('billing')->t('Quantity'),
            'reuse' => Yii::$app->getModule('billing')->t('Reuse'),
            'status' => Yii::$app->getModule('billing')->t('Status'),
            'created_at' => Yii::$app->getModule('billing')->t('Created At'),
            'updated_at' => Yii::$app->getModule('billing')->t('Updated At'),
            /* custom */
            'begin_date_picker' => Yii::$app->getModule('billing')->t('Begin Date'),
            'end_date_picker' => Yii::$app->getModule('billing')->t('End Date'),
        ];
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(is_a($this, '\yii\db\ActiveRecord')){
            $this->begin_at=strtotime($this->begin_at);
            $this->end_at=strtotime($this->end_at);
        }
        $this->discount=(float)$this->discount;
        $this->quantity=(int)$this->quantity;
        $this->reuse=(int)$this->reuse;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


}
