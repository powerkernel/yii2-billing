<?php

namespace powerkernel\billing\models;

use Yii;

/**
 * This is the model class for Setting.
 *
 * @property string $key
 * @property string $title
 * @property string $value
 * @property string $default
 * @property string $type
 * @property string $data
 * @property string $rules
 * @property integer $key_order
 */
class Setting extends \yii\mongodb\ActiveRecord
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'title'], 'required'],
            [['key_order'], 'integer'],
            [['key', 'title', 'value', 'type', 'default', 'data', 'rules'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => Yii::t('billing', 'Key'),
            'title' => Yii::t('billing', 'Title'),
            'value' => Yii::t('billing', 'Value'),
            'type' => Yii::t('billing', 'Type'),
            'default' => Yii::t('billing', 'Default'),
            'data' => Yii::t('billing', 'Data'),
            'rules' => Yii::t('billing', 'Rules'),
            'key_order' => Yii::t('billing', 'Order'),
        ];
    }

    /**
     * load as array
     * @return array
     */
    public static function loadAsArray()
    {
        $settings = self::find()->all();
        $a = [];
        foreach ($settings as $setting) {
            $a[$setting->key] = $setting->value;
        }
        return $a;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public static function findTitle($key)
    {
        $model = self::find()->where(['key' => $key])->one();
        if ($model) {
            return $model->title;
        }
        return null;
    }

    /**
     * get setting value
     * @param $key
     * @return mixed|null
     */
    public static function getValue($key)
    {
        $model = self::find()->where(['key' => $key])->one();
        if ($model) {
            return $model->value;
        }
        return null;
    }
}
