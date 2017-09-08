<?php

use modernkernel\billing\models\Invoice;
use yii\db\Migration;

/**
 * Class m170831_154226_update
 */
class m170831_154226_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('{{%billing_invoice}}', 'id', 'id_invoice');

        $this->dropForeignKey('fk_billing_item-billing_invoice', '{{%billing_item}}');
        $this->dropIndex('fk_billing_item-billing_invoice', '{{%billing_item}}');

        $this->dropForeignKey('fk_billing_invoice-core_account', '{{%billing_invoice}}');
        $this->dropIndex('fk_billing_invoice-core_account', '{{%billing_invoice}}');

        $this->dropForeignKey('fk_bitcoin_payments_id_invoice-invoice_id', '{{%billing_bitcoin_payments}}');
        $this->dropIndex('fk_bitcoin_payments_id_invoice-invoice_id', '{{%billing_bitcoin_payments}}');

        $this->dropPrimaryKey('pk', '{{%billing_invoice}}');

        $this->addColumn('{{%billing_invoice}}', 'id', $this->primaryKey()->first());

        $this->alterColumn('{{%billing_invoice}}', 'status', $this->string(50));
        $this->update('{{%billing_invoice}}', ['status'=> Invoice::STATUS_PENDING], ['status'=>'10']);
        $this->update('{{%billing_invoice}}', ['status'=> Invoice::STATUS_PAID], ['status'=>'20']);
        $this->update('{{%billing_invoice}}', ['status'=> Invoice::STATUS_CANCELED], ['status'=>'30']);
        $this->update('{{%billing_invoice}}', ['status'=> Invoice::STATUS_REFUNDED], ['status'=>'40']);
        $this->update('{{%billing_invoice}}', ['status'=> Invoice::STATUS_PAID_UNCONFIRMED], ['status'=>'50']);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%billing_invoice}}', 'status', $this->smallInteger());
        $this->update('{{%billing_invoice}}', ['status'=>10], ['status'=> Invoice::STATUS_PENDING]);
        $this->update('{{%billing_invoice}}', ['status'=>20], ['status'=> Invoice::STATUS_PAID]);
        $this->update('{{%billing_invoice}}', ['status'=>30], ['status'=> Invoice::STATUS_CANCELED]);
        $this->update('{{%billing_invoice}}', ['status'=>40], ['status'=> Invoice::STATUS_REFUNDED]);
        $this->update('{{%billing_invoice}}', ['status'=>50], ['status'=> Invoice::STATUS_PAID_UNCONFIRMED]);
        $this->dropColumn('{{%billing_invoice}}', 'id');
        $this->addPrimaryKey('pk', '{{%billing_invoice}}', 'id_invoice');
        $this->renameColumn('{{%billing_invoice}}', 'id_invoice', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170831_154226_update cannot be reverted.\n";

        return false;
    }
    */
}
