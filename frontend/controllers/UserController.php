<?php


namespace frontend\controllers;

use Yii;
use yii\base\BaseObject;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionProfile(){
        return $this->render('profile', [
            'user' => Yii::$app->user->identity,
            'likedArticles'=>Yii::$app->user->identity->likedArticles,
            'commentedArticles'=>Yii::$app->user->identity->commentedArticles

        ]);
    }
}