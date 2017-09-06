<?php

use yii\db\Migration;

/**
 * Class m170906_091237_setting
 */
class m170906_091237_setting extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        /* settings */
        $this->createTable('{{%billing_settings}}', [
            'key' => $this->string()->notNull(),
            'value' => $this->string()->null(),
            'title' => $this->string()->null(),
            'group' => $this->string()->null(),
            'type' => $this->string()->null(),
            'data' => $this->string()->null(),
            'default' => $this->string()->null(),
            'rules' => $this->string()->null(),
            'key_order' => $this->integer()->defaultValue(0),

        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%billing_settings}}', 'key');
        return true;
        $this->insert('{{%billing_settings}}', ['key' => 'merchantName', 'title'=>'Merchant Name', 'value' => '', 'type'=>'textInput', 'data'=>'[]', 'rules'=>json_encode(['required' => [], 'string' => []])]);

        $this->insert('{{%billing_settings}}', ['key' => 'merchantAddress', 'title'=>'Merchant Address', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'merchantCity', 'title'=>'Merchant City', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'merchantState', 'title'=>'Merchant State', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'merchantZip', 'title'=>'Merchant Zip', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'merchantCountry', 'title'=>'Merchant Country', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'merchantPhone', 'title'=>'Merchant Phone', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'merchantEmail', 'title'=>'Merchant Email', 'value' => '']);

        $this->insert('{{%billing_settings}}', ['key' => 'paypalSandbox', 'title'=>'Paypal Sandbox Mode', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'paypalClientID', 'title'=>'Paypal Client ID', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'paypalSecret', 'title'=>'Paypal Secret', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'paypalSandboxClientID', 'title'=>'Paypal Sandbox Client ID', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'paypalSandboxSecret', 'title'=>'Paypal Sandbox Secret', 'value' => '']);

        $this->insert('{{%billing_settings}}', ['key' => 'btcPaymentTime', 'title'=>'BTC Payment Timeout', 'value' => '']);
        $this->insert('{{%billing_settings}}', ['key' => 'btcWalletXPub', 'title'=>'BTC Wallet xPub', 'value' => '']);

        $this->insert('{{%billing_settings}}', ['key' => 'currencyLayerAPI', 'title'=>'Currency Layer API', 'value' => '']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%billing_settings}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170906_091237_setting cannot be reverted.\n";

        return false;
    }
    */
}
