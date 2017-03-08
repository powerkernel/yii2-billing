<?php

use yii\db\Migration;

/**
 * Class m170308_030159_update
 */
class m170308_030159_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // billing_info
        $this->addColumn('{{%billing_info}}', 'tax_id', $this->string()->null()->after('company'));
        /* banking */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%billing_bank}}', [
            'id' => $this->primaryKey(),
            'country' => $this->string(2)->notNull(),
            'title' => $this->string()->notNull(),
            'info' => $this->text()->notNull(),
            'currency' => $this->string(3)->notNull()->defaultValue('USD'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%billing_bank}}');
        $this->dropColumn('{{%billing_info}}', 'tax_id');
    }


}
