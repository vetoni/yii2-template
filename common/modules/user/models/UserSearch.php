<?php

namespace common\modules\user\models;

use yii\data\ActiveDataProvider;

/**
 * Class UserSearch
 * @package common\modules\user\models
 */
class UserSearch extends User
{
    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'username', 'email', 'status', 'created_at', 'roles'], 'safe']
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()
            ->from('{{%user}} u')
            ->leftJoin('{{%auth_assignment}} a', 'u.id = a.user_id')
            ->groupBy('u.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->andWhere('1!=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'u.status' => $this->status,
            'u.id' => $this->id
        ]);
        $query->andFilterWhere(['like', 'u.username', $this->username]);
        $query->andFilterWhere(['like', 'u.email', $this->email]);

        if ($this->created_at) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'u.created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['a.item_name' => $this->roles]);
        $query->orderBy(['u.id' => SORT_DESC]);
        return $dataProvider;
    }
}