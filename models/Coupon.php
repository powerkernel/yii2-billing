<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace modernkernel\billing\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%billing_coupon}}".
 *
 * @property string $code
 * @property string $currency
 * @property string $discount
 * @property string $discount_type
 * @property integer $begin_at
 * @property integer $end_at
 * @property integer $quantity
 * @property integer $reuse
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Coupon extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20;

    const DISCOUNT_TYPE_PERCENT = 10;
    const DISCOUNT_TYPE_VALUE = 20;

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
    public static function tableName()
    {
        return '{{%billing_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'currency', 'discount', 'discount_type', 'begin_at', 'end_at', 'quantity'], 'required'],
            [['discount'], 'number'],
            [['begin_at', 'end_at', 'quantity', 'reuse', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 50],

            [['begin_date_picker', 'end_date_picker'], 'required', 'on' => ['create']],

            [['end_at'], 'compare', 'compareAttribute'=>'begin_at', 'operator'=>'>=']
        ];
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
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
