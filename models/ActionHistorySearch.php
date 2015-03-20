<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ActionHistory;

/**
 * ActionHistorySearch represents the model behind the search form about `app\models\ActionHistory`.
 */
class ActionHistorySearch extends ActionHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ACTION_HISTORY_ID', 'ACTION_HISTORY_ACTION', 'USER_USER_ID'], 'integer'],
            [['ACTION_HISTORY_DATE', 'ACTION_HISTORY_IP'], 'safe'],
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
        $query = ActionHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ACTION_HISTORY_ID' => $this->ACTION_HISTORY_ID,
            'ACTION_HISTORY_DATE' => $this->ACTION_HISTORY_DATE,
            'ACTION_HISTORY_ACTION' => $this->ACTION_HISTORY_ACTION,
            'USER_USER_ID' => $this->USER_USER_ID,
        ]);

        $query->andFilterWhere(['like', 'ACTION_HISTORY_IP', $this->ACTION_HISTORY_IP]);

        return $dataProvider;
    }
}
