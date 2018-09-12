<?php

namespace frontend\modules\api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\api\models\Request;

/**
 * RequestSearch represents the model behind the search form about `frontend\modules\api\models\Request`.
 */
class RequestSearch extends Request
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['create_time', 'request', 'request_data', 'response', 'response_data', 'user', 'version', 'header_data'], 'safe'],
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
        $query = Request::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['create_time'=>SORT_DESC]],
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
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'request', $this->request])
            ->andFilterWhere(['like', 'request_data', $this->request_data])
            ->andFilterWhere(['like', 'response', $this->response])
            ->andFilterWhere(['like', 'response_data', $this->response_data])
            ->andFilterWhere(['like', 'user', $this->user])
            ->andFilterWhere(['like', 'version', $this->version])
			->andFilterWhere(['like', 'header_data', $this->header_data]);

        return $dataProvider;
    }
}
