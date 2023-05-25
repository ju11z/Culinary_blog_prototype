<?php


namespace frontend\models;


use Yii;
use common\models\User;
use app\models\Article;
use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{
    public function rules()
    {
        return [
            [['id','article_id','user_id'], 'integer'],
            [['content'],'safe']
        ];
    }

    public static function tableName()
    {
        return 'comment';
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    public function init()
    {
        parent::init();

        $this->user_id = Yii::$app->user->identity->Id;
    }

}