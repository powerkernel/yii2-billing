<?php

use yii\db\Migration;

/**
 * Class m170601_062210_coupon
 */
class m170601_062210_coupon extends Migration
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
        $this->createTable('{{%billing_coupon}}', [
            'code' => $this->string(50)->notNull(),
            'currency' => $this->string(5)->notNull()->defaultValue('USD'),
            'discount' => $this->decimal(10,2)->notNull(),
            'discount_type' => $this->smallInteger()->notNull()->defaultValue(10),
            'begin_at' => $this->integer()->notNull(),
            'end_at' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'reuse' => $this->boolean()->notNull()->defaultValue(false),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%billing_coupon}}', ['code']);

        /* invoice */
        $this->addColumn('{{%billing_invoice}}', 'coupon', $this->string(50)->null()->after('id_account'));
        //$this->addColumn('{{%billing_item}}', 'original_price', $this->money(19,2)->null()->after('price'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        //$this->dropColumn('{{%billing_item}}', 'original_price');
        $this->dropColumn('{{%billing_invoice}}', 'coupon');
        $this->dropTable('{{%billing_coupon}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
