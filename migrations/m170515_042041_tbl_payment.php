<?php

use yii\db\Migration;

/**
 * @inheritdoc
 * Class m170515_042041_tbl_payment
 */
class m170515_042041_tbl_payment extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%billing_bitcoin_payments}}', [
            'id'=>$this->primaryKey(),
            'address'=>$this->string(35)->notNull()->unique(),

            'id_invoice'=>$this->string(23)->null()->defaultValue(null),
            'id_account'=>$this->integer()->null()->defaultValue(null),

            'request_balance'=> $this->decimal(20,8)->notNull()->defaultValue(0.00000000),
            'total_received'=> $this->decimal(20,8)->notNull()->defaultValue(0.00000000),
            'final_balance'=> $this->decimal(20,8)->notNull()->defaultValue(0.00000000),



            'tx_id'=> $this->string(64)->null()->defaultValue(null),
            'tx_date'=> $this->integer()->null()->defaultValue(null),
            'tx_confirmed'=>$this->smallInteger()->notNull()->defaultValue(0),
            'tx_check_date'=>$this->integer()->null()->defaultValue(null),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

        ], $tableOptions);
        $this->addForeignKey('fk_bitcoin_payments_id_invoice-invoice_id', '{{%billing_bitcoin_payments}}', 'id_invoice', '{{%billing_invoice}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_bitcoin_payments_id_invoice-invoice_id', '{{%billing_bitcoin_payments}}');
        $this->dropTable('{{%billing_bitcoin_payments}}');
    }

}
