<?php

/**
 * Class m170907_104245_init
 */
class m170907_104245_init extends \yii\mongodb\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $col=Yii::$app->mongodb->getCollection('billing_settings');
        $col->createIndexes([
            [
                'key'=>['key'],
                'unique'=>true,
            ]
        ]);

        $col=Yii::$app->mongodb->getCollection('billing_invoice');
        $col->createIndexes([
            [
                'key'=>['id_invoice'],
                'unique'=>true,
            ]
        ]);

        $col=Yii::$app->mongodb->getCollection('billing_coupon');
        $col->createIndexes([
            [
                'key'=>['code'],
                'unique'=>true,
            ]
        ]);

        $col=Yii::$app->mongodb->getCollection('billing_bitcoin_payments');
        $col->createIndexes([
            [
                'key'=>['address'],
                'unique'=>true,
            ]
        ]);


    }

    /**
     * @return bool
     */
    public function down()
    {
        echo "m170907_104245_init cannot be reverted.\n";

        return false;
    }
}
