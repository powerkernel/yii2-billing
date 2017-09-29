<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace modernkernel\billing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CouponSearch represents the model behind the search form about `modernkernel\billing\models\Coupon`.
 */
class CouponSearch extends Coupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'begin_at', 'end_at', 'status', 'discount', 'quantity'], 'safe'],
            [['discount'], 'number'],
            //[['quantity', 'reuse', 'status', 'created_at', 'updated_at'], 'integer'],
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
        $query = Coupon::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            //'pagination'=>['pageSize'=>20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'code', $this->code]);
        $query->andFilterWhere([
            'discount' => in_array($this->discount, ['', null], true)?null:(float)$this->discount,
            'quantity' => in_array($this->quantity, ['', null], true)?null:(float)$this->quantity,
            'status' => $this->status,
        ]);
        if (!empty($this->begin_at)) {
            if (is_a($this, '\yii\mongodb\ActiveRecord')) {
                $query->andFilterWhere([
                    'begin_at' => ['$gte' => new \MongoDB\BSON\UTCDateTime(strtotime($this->begin_at) * 1000)],
                ])->andFilterWhere([
                    'begin_at' => ['$lt' => new \MongoDB\BSON\UTCDateTime((strtotime($this->begin_at) + 86400) * 1000)],
                ]);
            }
        }
        if (!empty($this->end_at)) {
            if (is_a($this, '\yii\mongodb\ActiveRecord')) {
                $query->andFilterWhere([
                    'end_at' => ['$gte' => new \MongoDB\BSON\UTCDateTime(strtotime($this->end_at) * 1000)],
                ])->andFilterWhere([
                    'end_at' => ['$lt' => new \MongoDB\BSON\UTCDateTime((strtotime($this->end_at) + 86400) * 1000)],
                ]);
            }
        }

//        if(!empty($this->begin_at)){
//            $query->andFilterWhere([
//                'DATE(CONVERT_TZ(FROM_UNIXTIME(`begin_at`), :UTC, :ATZ))' => $this->begin_at,
//            ])->params([
//                ':UTC'=>'+00:00',
//                ':ATZ'=>date('P')
//            ]);
//        }
//        if(!empty($this->end_at)){
//            $query->andFilterWhere([
//                'DATE(CONVERT_TZ(FROM_UNIXTIME(`end_at`), :UTC, :ATZ))' => $this->end_at,
//            ])->params([
//                ':UTC'=>'+00:00',
//                ':ATZ'=>date('P')
//            ]);
//        }

        return $dataProvider;
    }
}
