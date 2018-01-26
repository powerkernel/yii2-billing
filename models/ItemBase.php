<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace powerkernel\billing\models;


use Yii;


if (Yii::$app->getModule('billing')->params['db'] === 'mongodb') {
    /**
     * Class ItemActiveRecord
     * @package powerkernel\billing\models
     */
    class ItemActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'billing_item';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'id_invoice',
                'name',
                'quantity',
                'price',
                'details',
                'status'
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


    }
} else {
    /**
     * Class ItemActiveRecord
     * @package powerkernel\billing\models
     */
    class ItemActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%billing_item}}';
        }


    }
}

/**
 * Class ItemBase
 * @package powerkernel\billing\models
 */
class ItemBase extends ItemActiveRecord
{
}