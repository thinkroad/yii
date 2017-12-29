<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form about `common\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * 对对象的属性增加属性，使用array_merge增加属性
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['authorName']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title', 'content', 'tags', 'authorName'], 'safe'],
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
        $query = Post::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>6],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                'attributes' => ['id', 'title', 'authorName']

            ]
        ]);


/*
        echo '<pre>';
        print_r($dataProvider->getPagination());

        echo '<hr>';
        // 显示支持哪几个属性可以排序，按照什么规则排
        print_r($dataProvider->getSort());

        echo '<hr>';
        // 当前页的条数
        print_r($dataProvider->getCount());

        echo '<hr>';
        // 总条数
        print_r($dataProvider->getTotalCount());

        print_r($dataProvider->getModels());
*/


        // load块赋值，方法是把表单中填写的数据赋值给当前对象的属性
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'post.id' => $this->id,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        // 增加多表联查
        $query->join('INNER JOIN', 'Adminuser', 'post.author_id = Adminuser.id');
        $query->andFilterWhere(['like', 'Adminuser.nickname', $this->authorName]);

        // 增加可点击排序,这个功能有两种方法可以实现，推荐使用这个方法
        $dataProvider->sort->attributes['authorName'] = [
            'asc' => ['Adminuser.nickname' => SORT_ASC],
            'desc' => ['Adminuser.nickname' => SORT_DESC],
        ];






        return $dataProvider;
    }
}
