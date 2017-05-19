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
 * BitcoinAddressSearch represents the model behind the search form about `modernkernel\billing\models\BitcoinAddress`.
 */
class BitcoinAddressSearch extends BitcoinAddress
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'id_invoice', 'tx_id'], 'safe'],
            [['id', 'id_account', 'tx_date', 'tx_confirmed', 'tx_check_date', 'status', 'created_at', 'updated_at'], 'integer'],
            [['request_balance', 'total_received', 'final_balance'], 'number'],
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
        $query = BitcoinAddress::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            //'pagination'=>['pageSize'=>20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_account' => $this->id_account,
            'request_balance' => $this->request_balance,
            'total_received' => $this->total_received,
            'final_balance' => $this->final_balance,
            'tx_date' => $this->tx_date,
            'tx_confirmed' => $this->tx_confirmed,
            'tx_check_date' => $this->tx_check_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'id_invoice', $this->id_invoice])
            ->andFilterWhere(['like', 'tx_id', $this->tx_id]);

        //$query->andFilterWhere([
        //    'DATE(FROM_UNIXTIME(`created_at`))' => $this->created_at,
        //]);

        return $dataProvider;
    }
}
