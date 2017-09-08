<?php

use yii\db\Migration;

/**
 * Class m170831_043541_update
 */
class m170831_043541_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        /* bank */
        $this->alterColumn('{{%billing_bank}}', 'status', $this->string(50));
        $this->update('{{%billing_bank}}', ['status' => \modernkernel\billing\models\Bank::STATUS_ACTIVE], ['status' => '10']);
        $this->update('{{%billing_bank}}', ['status' => \modernkernel\billing\models\Bank::STATUS_INACTIVE], ['status' => '20']);
        /* btc */
        $this->alterColumn('{{%billing_bitcoin_payments}}', 'status', $this->string(50));
        $this->update('{{%billing_bitcoin_payments}}', ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_NEW], ['status' => '10']);
        $this->update('{{%billing_bitcoin_payments}}', ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_USED], ['status' => '20']);
        $this->update('{{%billing_bitcoin_payments}}', ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_DONE], ['status' => '30']);
        $this->update('{{%billing_bitcoin_payments}}', ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_UNCONFIRMED], ['status' => '40']);
        /* coupon */
        $this->dropPrimaryKey('pk', '{{%billing_coupon}}');
        $this->addColumn('{{%billing_coupon}}','id', $this->primaryKey()->first());
        $this->alterColumn('{{%billing_coupon}}', 'status', $this->string(50));
        $this->alterColumn('{{%billing_coupon}}', 'discount_type', $this->string(50));
        $this->update('{{%billing_coupon}}', ['status' => \modernkernel\billing\models\Coupon::STATUS_ACTIVE], ['status' => '10']);
        $this->update('{{%billing_coupon}}', ['status' => \modernkernel\billing\models\Coupon::STATUS_INACTIVE], ['status' => '20']);
        $this->update('{{%billing_coupon}}', ['discount_type' => \modernkernel\billing\models\Coupon::DISCOUNT_TYPE_PERCENT], ['discount_type' => '10']);
        $this->update('{{%billing_coupon}}', ['discount_type' => \modernkernel\billing\models\Coupon::DISCOUNT_TYPE_VALUE], ['discount_type' => '20']);

        /* info */
        $this->dropPrimaryKey('pk', '{{%billing_info}}');
        $this->addColumn('{{%billing_info}}', 'id', $this->primaryKey()->first());
        $this->alterColumn('{{%billing_info}}', 'status', $this->string(50));
        $this->update('{{%billing_info}}', ['status' => \modernkernel\billing\models\BillingInfo::STATUS_ACTIVE], ['status' => '10']);
        $this->update('{{%billing_info}}', ['status' => \modernkernel\billing\models\BillingInfo::STATUS_INACTIVE], ['status' => '20']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%billing_info}}', 'id');
        $this->addPrimaryKey('pk', '{{%billing_info}}', 'id_account');
        $this->alterColumn('{{%billing_info}}', 'status', $this->smallInteger());
        $this->update('{{%billing_info}}', ['status' => 10], ['status' => \modernkernel\billing\models\BillingInfo::STATUS_ACTIVE]);
        $this->update('{{%billing_info}}', ['status' => 20], ['status' => \modernkernel\billing\models\BillingInfo::STATUS_INACTIVE]);

        /* coupon */
        $this->update('{{%billing_coupon}}', ['status' => 10], ['status' => \modernkernel\billing\models\Coupon::STATUS_ACTIVE]);
        $this->update('{{%billing_coupon}}', ['status' => 20], ['status' => \modernkernel\billing\models\Coupon::STATUS_INACTIVE]);
        $this->update('{{%billing_coupon}}', ['discount_type' => 10], ['discount_type' => \modernkernel\billing\models\Coupon::DISCOUNT_TYPE_PERCENT]);
        $this->update('{{%billing_coupon}}', ['discount_type' => 20], ['discount_type' => \modernkernel\billing\models\Coupon::DISCOUNT_TYPE_VALUE]);
        $this->alterColumn('{{%billing_coupon}}', 'status', $this->smallInteger());
        $this->alterColumn('{{%billing_coupon}}', 'discount_type', $this->smallInteger());
        $this->dropColumn('{{%billing_coupon}}', 'id');
        $this->addPrimaryKey('pk', '{{%billing_coupon}}', 'code');

        /* btc */
        $this->update('{{%billing_bitcoin_payments}}', ['status' => 10], ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_NEW]);
        $this->update('{{%billing_bitcoin_payments}}', ['status' => 20], ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_USED]);
        $this->update('{{%billing_bitcoin_payments}}', ['status' => 30], ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_DONE]);
        $this->update('{{%billing_bitcoin_payments}}', ['status' => 40], ['status' => \modernkernel\billing\models\BitcoinAddress::STATUS_UNCONFIRMED]);
        $this->alterColumn('{{%billing_bitcoin_payments}}', 'status', $this->smallInteger());

        /* bank */
        $this->update('{{%billing_bank}}', ['status' => 10], ['status' => \modernkernel\billing\models\Bank::STATUS_ACTIVE]);
        $this->update('{{%billing_bank}}', ['status' => 20], ['status' => \modernkernel\billing\models\Bank::STATUS_INACTIVE]);
        $this->alterColumn('{{%billing_bank}}', 'status', $this->smallInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170831_043541_update cannot be reverted.\n";

        return false;
    }
    */
}
