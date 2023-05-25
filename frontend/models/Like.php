<?php


namespace frontend\models;


use yii\db\ActiveRecord;
use frontend\models\Article;

class Like extends ActiveRecord
{
    public static function tableName()
    {
        return 'like';
    }

    public function getArticle(){
        return $this->hasOne(Article::className(),['id'=>'article_id']);
    }
}