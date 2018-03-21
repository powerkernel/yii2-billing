<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace powerkernel\billing\models;

use common\behaviors\UTCDateTimeBehavior;
use Yii;

/**
 * This is the model class for Bank.
 *
 * @property \MongoDB\BSON\ObjectID|string $id
 * @property string $country
 * @property string $title
 * @property string $info
 * @property string $currency
 * @property string $status
 * @property \MongoDB\BSON\UTCDateTime $created_at
 * @property \MongoDB\BSON\UTCDateTime $updated_at
 */
class Bank extends \yii\mongodb\ActiveRecord
{

    const STATUS_ACTIVE = 'STATUS_ACTIVE'; //10
    const STATUS_INACTIVE = 'STATUS_INACTIVE'; //20

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'billing_bank';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'country',
            'title',
            'info',
            'currency',
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
            UTCDateTimeBehavior::class,
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
     * color status text
     * @return mixed|string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        if ($status == self::STATUS_ACTIVE) {
            return '<span class="label label-success">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_INACTIVE) {
            return '<span class="label label-default">' . $this->statusText . '</span>';
        }
        return $this->statusText;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'title', 'info', 'currency'], 'required'],
            [['info', 'status'], 'string'],
            [['country'], 'string', 'max' => 2],
            [['title'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 3],
            [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::$app->getModule('billing')->t('ID'),
            'country' => Yii::$app->getModule('billing')->t('Country'),
            'title' => Yii::$app->getModule('billing')->t('Title'),
            'info' => Yii::$app->getModule('billing')->t('Info'),
            'currency' => Yii::$app->getModule('billing')->t('Currency'),
            'status' => Yii::$app->getModule('billing')->t('Status'),
            'created_at' => Yii::$app->getModule('billing')->t('Created At'),
            'updated_at' => Yii::$app->getModule('billing')->t('Updated At'),
        ];
    }


}
