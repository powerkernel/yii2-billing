<?php

use yii\db\Migration;

/**
 * Class m170906_091237_setting
 */
class m170906_091237_setting extends Migration
{
    /**
     * @inheritdoc
     */
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

        /* copy setting */
        $settings=\common\models\Setting::find()->where(['group'=>'Billing'])->all();
        foreach($settings as $setting){
            $this->insert('{{%billing_settings}}', [
                'key'=>$setting->key,
                'value'=>$setting->value,
                'title'=>$setting->title,
                'group'=>$setting->group,
                'type'=>$setting->type,
                'data'=>$setting->data,
                'default'=>$setting->default,
                'rules'=>$setting->rules,
                'key_order'=>$setting->key_order,
            ]);
        }
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
