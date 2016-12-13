<?php

use yii\db\Migration;

/**
 * Class m161207_071322_init
 */
class m161207_071322_init extends Migration
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

        /* invoice */
        $this->createTable('{{%billing_invoice}}', [
            'id' => $this->string(23)->notNull(),
            'id_account' => $this->integer()->null(),

            'subtotal' => $this->money(19,2)->notNull()->defaultValue(0),
            'discount' => $this->money(19,2)->notNull()->defaultValue(0),
            'tax' => $this->money(19,2)->notNull()->defaultValue(0),
            'total' => $this->money(19,2)->notNull()->defaultValue(0),
            'currency' => $this->string(3)->notNull()->defaultValue('USD'),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%billing_invoice}}', 'id');
        $this->addForeignKey('fk_billing_invoice-core_account', '{{%billing_invoice}}', 'id_account', '{{%core_account}}', 'id');

        /* invoice item */
        $this->createTable('{{%billing_item}}', [
            'id' => $this->primaryKey(),
            'id_invoice' => $this->string(23)->notNull(),
            'name' => $this->string()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(0),
            'price' => $this->money(19,2)->notNull()->defaultValue(0),
            'details' => $this->text()->null(),
        ], $tableOptions);
        $this->addForeignKey('fk_billing_item-billing_invoice', '{{%billing_item}}', 'id_invoice', '{{%billing_invoice}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_billing_item-billing_invoice', '{{%billing_item}}');
        $this->dropTable('{{%billing_item}}');
        $this->dropForeignKey('fk_billing_invoice-core_account', '{{%billing_invoice}}');
        $this->dropTable('{{%billing_invoice}}');
    }

}
