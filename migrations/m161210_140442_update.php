<?php

use yii\db\Migration;

/**
 * Class m161210_140442_update
 */
class m161210_140442_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%billing_invoice}}', 'payment_method', $this->string(50)->null()->after('currency'));
        $this->addColumn('{{%billing_invoice}}', 'payment_date', $this->integer()->null()->after('payment_method'));
        $this->addColumn('{{%billing_invoice}}', 'transaction', $this->string(50)->null()->after('payment_date'));
        $this->addColumn('{{%billing_invoice}}', 'info', $this->text()->null()->after('transaction'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%billing_invoice}}', 'info');
        $this->dropColumn('{{%billing_invoice}}', 'transaction');
        $this->dropColumn('{{%billing_invoice}}', 'payment_date');
        $this->dropColumn('{{%billing_invoice}}', 'payment_method');
    }


}
