<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace powerkernel\billing\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use common\behaviors\UTCDateTimeBehavior;


if (Yii::$app->getModule('billing')->params['db'] === 'mongodb') {
    /**
     * Class InvoiceActiveRecord
     */
    class InvoiceActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'billing_invoice';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'id_invoice',
                'id_account',
                'coupon',
                'subtotal',
                'shipping',
                'tax',
                'total',
                'currency',
                'payment_method',
                'payment_date',
                'transaction',
                'info',
                'shipping_info',
                'note',
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
        public function getPaymentDate()
        {
            return empty($this->payment_date)?null:$this->payment_date->toDateTime()->format('U');
        }
    }
} else {
    /**
     * Class InvoiceActiveRecord
     */
    class InvoiceActiveRecord extends \yii\db\ActiveRecord
    {
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
        public function getPaymentDate()
        {
            return $this->payment_date;
        }
    }
}

/**
 * Class InvoiceActiveRecord
 */
class InvoiceBase extends InvoiceActiveRecord
{
}