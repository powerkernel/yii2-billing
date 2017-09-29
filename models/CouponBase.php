<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace modernkernel\billing\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use common\behaviors\UTCDateTimeBehavior;


if (Yii::$app->getModule('billing')->params['db'] === 'mongodb') {
    /**
     * Class CouponActiveRecord
     * @package common\models
     */
    class CouponActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'billing_coupon';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'code',
                'currency',
                'discount',
                'discount_type',
                'begin_at',
                'end_at',
                'quantity',
                'reuse',
                'status',
                'created_at',
                'updated_at',
            ];
        }

        /**
         * get id
         * @return \MongoDB\BSON\ObjectID|string
         */
        public function getId()
        {
            return $this->_id;
        }

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                UTCDateTimeBehavior::className(),
            ];
        }

        /**
         * @return int timestamp
         */
        public function getUpdatedAt()
        {
            return $this->updated_at->toDateTime()->format('U');
        }

        /**
         * @return int timestamp
         */
        public function getCreatedAt()
        {
            return $this->created_at->toDateTime()->format('U');
        }

        /**
         * @return int timestamp
         */
        public function getBeginAt()
        {
            return $this->begin_at->toDateTime()->format('U');
        }

        /**
         * @return int timestamp
         */
        public function getEndAt()
        {
            return $this->end_at->toDateTime()->format('U');
        }
    }
} else {
    /**
     * Class CouponActiveRecord
     * @package common\models
     */
    class CouponActiveRecord extends \yii\db\ActiveRecord
    {
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
        public function behaviors()
        {
            return [
                TimestampBehavior::className(),
            ];
        }

        /**
         * @return int timestamp
         */
        public function getUpdatedAt()
        {
            return $this->updated_at;
        }

        /**
         * @return int timestamp
         */
        public function getCreatedAt()
        {
            return $this->created_at;
        }

        /**
         * @return int timestamp
         */
        public function getBeginAt(){
            return $this->begin_at;
        }

        /**
         * @return int timestamp
         */
        public function getEndAt(){
            return $this->end_at;
        }
    }
}

/**
 * Class CouponBase
 * @package common\models
 */
class CouponBase extends CouponActiveRecord
{
}