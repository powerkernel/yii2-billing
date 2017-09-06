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
     * Class SettingActiveRecord
     */
    class SettingActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'billing_settings';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'key',
                'value',
                'title',
                'group',
                'type',
                'default',
                'data',
                'rules',
                'key_order'
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
     * Class SettingActiveRecord
     */
    class SettingActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%billing_settings}}';
        }


    }
}

/**
 * Class SettingBase
 */
class SettingBase extends SettingActiveRecord
{
}