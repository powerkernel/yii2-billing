<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace modernkernel\billing\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use modernkernel\billing\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `modernkernel\billing\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{

    public $fullname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'currency', 'created_at', 'fullname'], 'safe'],
            [['id_account', 'status', 'updated_at'], 'integer'],
            [['subtotal', 'discount', 'tax', 'total'], 'number'],
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
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['fullname'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['{{%core_account}}.fullname' => SORT_ASC],
            'desc' => ['{{%core_account}}.fullname' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_account' => $this->id_account,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'total' => $this->total,
            'status' => $this->status,
            //'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', '{{%core_account}}.fullname', $this->fullname]);;

        $query->andFilterWhere([
            'DATE(FROM_UNIXTIME(`created_at`))' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
