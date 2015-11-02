<?php
/**
 * This file is part of Levya Identity.
 * 
 * Levya Identity is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Foobar. See file LICENSE(.md) in this source tree, 
 * if not, see <http://www.gnu.org/licenses/>.
 * 
 * Copyright (C) Levya Team Members
 */

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['USER_ID', 'USERSTATE_USERSTATE_ID', 'COUNTRY_COUNTRY_ID'], 'integer'],
            [['USER_LASTNAME', 'USER_FORNAME', 'USER_MAIL', 'USER_NICKNAME', 'USER_PASSWORD', 'USER_ADDRESS', 'USER_PHONE', 'USER_SECRETKEY', 'USER_CREATIONDATE', 'USER_REGISTRATIONDATE', 'USER_REGISTRATIONIP', 'USER_UPDATEDATE', 'USER_AUTHKEY', 'USER_LDAPUID'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'USER_ID' => $this->USER_ID,
            'USER_CREATIONDATE' => $this->USER_CREATIONDATE,
            'USER_REGISTRATIONDATE' => $this->USER_REGISTRATIONDATE,
            'USER_UPDATEDATE' => $this->USER_UPDATEDATE,
            'USERSTATE_USERSTATE_ID' => $this->USERSTATE_USERSTATE_ID,
            'COUNTRY_COUNTRY_ID' => $this->COUNTRIE_COUNTRY_ID,
        ]);

        $query->andFilterWhere(['like', 'USER_LASTNAME', $this->USER_LASTNAME])
            ->andFilterWhere(['like', 'USER_FORNAME', $this->USER_FORNAME])
            ->andFilterWhere(['like', 'USER_MAIL', $this->USER_MAIL])
            ->andFilterWhere(['like', 'USER_NICKNAME', $this->USER_NICKNAME])
            ->andFilterWhere(['like', 'USER_ADDRESS', $this->USER_ADDRESS])
            ->andFilterWhere(['like', 'USER_PHONE', $this->USER_PHONE])
            ->andFilterWhere(['like', 'USER_REGISTRATIONIP', $this->USER_REGISTRATIONIP])
            ->andFilterWhere(['like', 'USER_LDAPUID', $this->USER_LDAPUID]);

        return $dataProvider;
    }
}
