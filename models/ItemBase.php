<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace modernkernel\billing\models;


use Yii;


if (Yii::$app->params['billing']['db'] === 'mongodb') {
    /**
     * Class ItemActiveRecord
     * @package modernkernel\billing\models
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
     * @package modernkernel\billing\models
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
 * @package modernkernel\billing\models
 */
class ItemBase extends ItemActiveRecord
{
}