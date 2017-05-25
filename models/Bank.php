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
 * This is the model class for table "{{%billing_bank}}".
 *
 * @property integer $id
 * @property string $country
 * @property string $title
 * @property string $info
 * @property string $currency
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Bank extends ActiveRecord
{


    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20;


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
    public static function tableName()
    {
        return '{{%billing_bank}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'title', 'info', 'currency'], 'required'],
            [['info'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['country'], 'string', 'max' => 2],
            [['title'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 3],
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
