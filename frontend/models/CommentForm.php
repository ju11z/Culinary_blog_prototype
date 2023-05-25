<?php


namespace frontend\models;


use yii\base\BaseObject;

class CommentForm extends Comment
{
    public $comment;

    public function rules(){
        return [
            [['comment'],'required'],
            [['comment'],'string','length'=>[3,250]]
        ];
    }

    public function saveComment($article_id){
        $comment=new Comment();
        $comment->content=$this->comment;
        $comment->user_id=3;
        $comment->article_id=$article_id;

        return $comment->save();
    }
}