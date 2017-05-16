<?php
/**
* @author Harry Tang <harry@modernkernel.com>
* @link https://modernkernel.com
* @copyright Copyright (c) 2017 Modern Kernel
*/

namespace modernkernel\billing\models;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKeyFactory;
use common\models\Setting;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%billing_bitcoin_payments}}".
 *
 * @property string $address
 * @property string $id_invoice
 * @property integer $id_account
 * @property string $total_received
 * @property string $final_balance
 * @property string $tx_id
 * @property integer $tx_date
 * @property integer $tx_confirmed
 * @property integer $tx_check_date
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class BitcoinAddress extends ActiveRecord
{


    const STATUS_NEW = 10;
    const STATUS_USED = 20;


    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
         self::STATUS_NEW => Yii::$app->getModule('billing')->t('New'),
         self::STATUS_USED => Yii::$app->getModule('billing')->t('Used'),
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
        $status=$this->status;
        $list=self::getStatusOption();
        if(!empty($status) && in_array($status, array_keys($list))){
            return $list[$status];
        }
        return Yii::$app->getModule('billing')->t('Unknown');
    }

    /**
     * get status color text
     * @return string
     */
    public function getStatusColorText(){
        $status = $this->status;
        $list = self::getStatusOption();

        $color='default';
        if($status==self::STATUS_NEW){
            $color='primary';
        }
        if($status==self::STATUS_USED){
            $color='default';
        }

        if (!empty($status) && in_array($status, array_keys($list))) {
            return '<span class="label label-'.$color.'">'.$list[$status].'</span>';
        }
        return '<span class="label label-'.$color.'">'.Yii::$app->getModule('ticket')->t('Unknown').'</span>';
    }



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_bitcoin_payments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address'], 'required'],
            [['id_account', 'tx_date', 'tx_confirmed', 'tx_check_date', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_received', 'final_balance'], 'number'],
            [['address'], 'string', 'max' => 35],
            [['id_invoice'], 'string', 'max' => 23],
            [['tx_id'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address' => Yii::$app->getModule('billing')->t('Address'),
            'id_invoice' => Yii::$app->getModule('billing')->t('Invoice'),
            'id_account' => Yii::$app->getModule('billing')->t('Account'),
            'total_received' => Yii::$app->getModule('billing')->t('Total Received'),
            'final_balance' => Yii::$app->getModule('billing')->t('Final Balance'),
            'tx_id' => Yii::$app->getModule('billing')->t('TX ID'),
            'tx_date' => Yii::$app->getModule('billing')->t('TX Date'),
            'tx_confirmed' => Yii::$app->getModule('billing')->t('TX Confirmed'),
            'tx_check_date' => Yii::$app->getModule('billing')->t('TX Check Date'),
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

    /**
     * generate btc address
     */
    public static function generate(){
        $xpub=Setting::getValue('btcWalletXPub');
        if(!empty($xpub)){
            $network = Bitcoin::getNetwork();
            $hk = HierarchicalKeyFactory::fromExtended($xpub, $network);
            /* count total new address*/
            $new=BitcoinAddress::find()->where(['status'=>self::STATUS_NEW])->count();
            /* only generate more address if < 20*/
            if($new<20){
                $child=BitcoinAddress::find()->count();
                $n=20-$new;
                for ($i = 0; $i < $n; $i++) {
                    $address = $hk->deriveChild($child + $i)->getPublicKey()->getAddress();
                    $addr= new BitcoinAddress();
                    $addr->address=$address->getAddress();
                    $addr->save();
                }
            }

        }
    }
}
