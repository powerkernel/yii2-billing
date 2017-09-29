<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace modernkernel\billing\models;

use common\Core;
use common\models\Account;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AddressSearch represents the model behind the search form about `modernkernel\billing\models\Address`.
 */
class AddressSearch extends Address
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_account', 'country', 'contact_name', 'street_address_1', 'street_address_2', 'city', 'state', 'zip_code', 'phone', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Address::find();
        $pageSize=20;
        if(Core::checkMCA('billing', 'address', 'manage')){
            $pageSize=9;
        }

        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            'pagination'=>['pageSize'=>$pageSize],
        ]);

        $this->load($params);

        /* manage action */
        if(Core::checkMCA('billing', 'address', 'manage')){
            $query->andFilterWhere(['like', 'id_account', Yii::$app->user->id]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // account
        if(!in_array($this->id_account, ['', null], true)) {
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
            if(empty($ids)){
                $ids[]='0';
            }
            $query->andFilterWhere(['id_account' => $ids]);
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id])
            //->andFilterWhere(['like', 'id_account', $this->id_account])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'street_address_1', $this->street_address_1])
            ->andFilterWhere(['like', 'street_address_2', $this->street_address_2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        //if(!empty($this->created_at)){
        //    $query->andFilterWhere([
        //        'DATE(CONVERT_TZ(FROM_UNIXTIME(`created_at`), :UTC, :ATZ))' => $this->created_at,
        //    ])->params([
        //        ':UTC'=>'+00:00',
        //        ':ATZ'=>date('P')
        //    ]);
        //}

        //if(!empty($this->created_by)) {
        //    $owners=Account::find()->select('id')->where(['like', 'fullname', $this->created_by])->asArray()->all();
        //    $ids = [0];
        //    foreach ($owners as $owner) {
        //        $ids[] = (integer)$owner['id'];
        //    }
        //    $query->andFilterWhere(['created_by' => $ids]);
        //}

        return $dataProvider;
    }
}
