<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace modernkernel\billing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * InvoiceSearch represents the model behind the search form about `modernkernel\billing\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{

    public $fullname;
    public $manage = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'currency', 'created_at', 'fullname'], 'safe'],
            [['id_account', 'status', 'updated_at'], 'integer'],
            [['subtotal', 'shipping', 'tax', 'total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Invoice::find();
        $query->joinWith(['account']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['fullname'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['{{%core_account}}.fullname' => SORT_ASC],
            'desc' => ['{{%core_account}}.fullname' => SORT_DESC],
        ];

        if ($this->manage) {
            $query->andWhere(['id_account' => \Yii::$app->user->id]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%billing_invoice}}.id_account' => $this->id_account,
            '{{%billing_invoice}}.subtotal' => $this->subtotal,
            '{{%billing_invoice}}.shipping' => $this->shipping,
            '{{%billing_invoice}}.tax' => $this->tax,
            '{{%billing_invoice}}.total' => $this->total,
            '{{%billing_invoice}}.status' => $this->status,
            //'created_at' => $this->created_at,
            '{{%billing_invoice}}.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', '{{%billing_invoice}}.id', $this->id])
            ->andFilterWhere(['like', '{{%billing_invoice}}.currency', $this->currency])
            ->andFilterWhere(['like', '{{%core_account}}.fullname', $this->fullname]);;

        if(!empty($this->created_at)){
            $query->andFilterWhere([
                'DATE(CONVERT_TZ(FROM_UNIXTIME({{%billing_invoice}}.created_at), :UTC, :ATZ))' => $this->created_at,
            ])->params([
                ':UTC'=>'+00:00',
                ':ATZ'=>date('P')
            ]);
        }

        return $dataProvider;
    }
}
