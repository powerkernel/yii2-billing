<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace powerkernel\billing\models;

use common\models\Account;
use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * InvoiceSearch represents the model behind the search form about `powerkernel\billing\models\Invoice`.
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
            [['id_invoice', 'total', 'created_at', 'id_account', 'status'], 'safe'],
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
        //$query->joinWith(['account']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['fullname'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            //'asc' => ['{{%core_account}}.fullname' => SORT_ASC],
            //'desc' => ['{{%core_account}}.fullname' => SORT_DESC],
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
            'total' => in_array($this->total, ['', null], true)?null:(float)$this->total,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'id_invoice', $this->id_invoice]);

        /* account */
        if(!empty($this->id_account)) {
            if(Yii::$app->params['mongodb']['account']){
                $key='_id';
            }
            else {
                $key='id';
            }
            $ids = [];
            $owners=Account::find()->select([$key])->where(['like', 'fullname', $this->id_account])->asArray()->all();
            foreach ($owners as $owner) {
                if(Yii::$app->params['mongodb']['account']){
                    $ids[] = (string)$owner[$key];
                }
                else {
                    $ids[] = (int)$owner[$key];
                }
            }
            $query->andFilterWhere(['id_account' => empty($ids)?'0':$ids]);
        }

        /* created_at */
        if(!empty($this->created_at)) {
            if (is_a($this, '\yii\mongodb\ActiveRecord')) {
                $query->andFilterWhere([
                    'updated_at' => ['$gte'=>new UTCDateTime(strtotime($this->created_at)*1000)],
                ])->andFilterWhere([
                    'updated_at' => ['$lt'=>new UTCDateTime((strtotime($this->created_at)+86400)*1000)],
                ]);
            } else {
                $query->andFilterWhere([
                    'DATE(CONVERT_TZ(FROM_UNIXTIME({{%billing_invoice}}.created_at), :UTC, :ATZ))' => $this->created_at,
                ])->params([
                    ':UTC' => '+00:00',
                    ':ATZ' => date('P')
                ]);
            }
        }

        return $dataProvider;
    }
}
