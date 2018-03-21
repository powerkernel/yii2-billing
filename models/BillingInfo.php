<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace powerkernel\billing\models;

use common\behaviors\UTCDateTimeBehavior;
use common\models\Account;
use Yii;

/**
 * This is the model class for BillingInfo.
 *
 * @property \MongoDB\BSON\ObjectID|string $id
 * @property string $id_account
 * @property string $company
 * @property string $tax_id
 * @property string $f_name
 * @property string $l_name
 * @property string $address
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $phone
 * @property string $status
 * @property \MongoDB\BSON\UTCDateTime $created_at
 * @property \MongoDB\BSON\UTCDateTime $updated_at
 */
class BillingInfo extends \yii\mongodb\ActiveRecord
{


    const STATUS_ACTIVE = 'STATUS_ACTIVE'; // 10
    const STATUS_INACTIVE = 'STATUS_INACTIVE'; // 20


    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'billing_info';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'id_account',
            'company',
            'tax_id',
            'f_name',
            'l_name',
            'address',
            'address2',
            'city',
            'state',
            'zip',
            'country',
            'phone',
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
            [['f_name', 'l_name', 'address', 'city', 'country', 'phone'], 'required'],

            [['company', 'tax_id', 'f_name', 'l_name', 'address', 'address2', 'city', 'state', 'zip', 'country', 'status'], 'string', 'max' => 255],

            [['phone'], 'string', 'max' => 14],
            [['phone'], 'match', 'pattern' => '/^\+[1-9][0-9]{9,12}$/'],
            [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator'],
            [['id_account'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_account' => Yii::$app->getModule('billing')->t('Account'),
            'company' => Yii::$app->getModule('billing')->t('Company'),
            'tax_id' => Yii::$app->getModule('billing')->t('Tax ID'),
            'f_name' => Yii::$app->getModule('billing')->t('First Name'),
            'l_name' => Yii::$app->getModule('billing')->t('Last Name'),
            'address' => Yii::$app->getModule('billing')->t('Address'),
            'address2' => Yii::$app->getModule('billing')->t('Address 2'),
            'city' => Yii::$app->getModule('billing')->t('City'),
            'state' => Yii::$app->getModule('billing')->t('State'),
            'zip' => Yii::$app->getModule('billing')->t('Zip'),
            'country' => Yii::$app->getModule('billing')->t('Country'),
            'phone' => Yii::$app->getModule('billing')->t('Phone'),
            'status' => Yii::$app->getModule('billing')->t('Status'),
            'created_at' => Yii::$app->getModule('billing')->t('Created At'),
            'updated_at' => Yii::$app->getModule('billing')->t('Updated At'),
        ];
    }


    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            if (empty($this->id_account)) {
                $this->id_account = Yii::$app->user->id;
            }
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        /* copy if no shipping address */
        if ($insert) {
            $count = Address::find()->where(['id_account' => Yii::$app->user->id])->count();
            if ($count == 0) {
                $addr = new Address();
                $addr->contact_name = $this->f_name . ' ' . $this->l_name;
                $addr->street_address_1 = $this->address;
                $addr->street_address_2 = $this->address2;
                $addr->city = $this->city;
                $addr->state = $this->state;
                $addr->country = $this->country;
                $addr->zip_code = $this->zip;
                $addr->phone = $this->phone;
                $addr->id_account = Yii::$app->user->id;
                $addr->save();
            }
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAccount()
    {
            return $this->hasOne(Account::class, ['_id' => 'id_account']);
    }

    /**
     * get info, return account full name if no info
     * @param $id
     * @return array
     */
    public static function getInfo($id)
    {
        $info = [
            'company' => '',
            'tax_id' => '',
            'f_name' => '',
            'l_name' => '',
            'address' => '',
            'address2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
            'phone' => '',
        ];

        $model = BillingInfo::find()->where(['id_account' => $id])->one();
        $account = Account::findOne($id);
        if ($model) {
            foreach ($info as $attr => $value) {
                $info[$attr] = $model->$attr;
            }
        } else {
            if ($account) {
                $info['f_name'] = $account->fullname;
            }
        }


        $info['email'] = $account->email;
        return $info;
    }
}
