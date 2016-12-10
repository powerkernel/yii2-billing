<?php

use yii\db\Migration;

/**
 * Class m161210_083054_tbl_billing_info
 */
class m161210_083054_tbl_billing_info extends Migration
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
        $this->createTable('{{%billing_info}}', [
            'id_account' => $this->integer()->notNull(),
            'company' => $this->string()->null(),

            'f_name' => $this->string()->notNull(),
            'l_name' => $this->string()->notNull(),

            'address' => $this->string()->notNull(),
            'address2' => $this->string()->null(),

            'city' => $this->string()->notNull(),
            'state' => $this->string()->null(),
            'zip' => $this->string()->null(),
            'country' => $this->string()->notNull(),

            'phone' => $this->string()->notNull(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

        ], $tableOptions);

        $this->addPrimaryKey('pk', '{{%billing_info}}', 'id_account');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%billing_info}}');
    }


}
