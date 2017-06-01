<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace modernkernel\billing\models;

use Yii;
use yii\base\Model;

/**
 * Class CouponForm
 * @package modernkernel\billing\models
 */
class CouponForm extends Model
{
    public $coupon;
    public $invoice;

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['coupon', 'invoice'], 'required'],
            [['coupon'], 'string', 'min' => 3, 'max' => 50],
            [['coupon'], 'checkCoupon']
        ];
    }

    /**
     * check coupon
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function checkCoupon($attribute, $params, $validator)
    {
//        if (!in_array($this->$attribute, ['USA', 'Web'])) {
//            $this->addError($attribute, 'The country must be either "USA" or "Web".');
//        }
        /* exist */
        $code = Coupon::find()->where([
            'code' => $this->$attribute,
            'status' => Coupon::STATUS_ACTIVE,
        ])->andWhere(':now>=`begin_at` AND :now<=`end_at`', [':now' => time()])
            ->one();
        if (!$code) {
            $this->addError($attribute, Yii::$app->getModule('billing')->t('The code you entered is invalid or expired.'));
            return false;
        }
        /* currency */
        if($code->currency!=$this->invoice->currency){
            $this->addError($attribute, Yii::$app->getModule('billing')->t('The invoice is not eligible for this promotion.'));
            return false;
        }

        /* Quantity */
        if($code->quantity!=-1){
            $used=Invoice::find()->where([
                'coupon'=>$code,
                'status'=>[Invoice::STATUS_PAID, Invoice::STATUS_PAID_UNCONFIRMED, Invoice::STATUS_REFUNDED]
            ])->count();
            if($code->quantity<=$used){
                $this->addError($attribute, Yii::$app->getModule('billing')->t('The code you entered is ended.'));
                return false;
            }
        }

        /* can reuse? */
        if(!$code->reuse){
            $reused=Invoice::find()->where([
                'coupon'=>$code,
                'status'=>[Invoice::STATUS_PAID, Invoice::STATUS_PAID_UNCONFIRMED, Invoice::STATUS_REFUNDED],
                'id_account'=>Yii::$app->user->id
            ])->count();
            if($reused){
                $this->addError($attribute, Yii::$app->getModule('billing')->t('You already used this code once!'));
                return false;
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon' => Yii::$app->getModule('billing')->t('Coupon Code'),
        ];
    }


}