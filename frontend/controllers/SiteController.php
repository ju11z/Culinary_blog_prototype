<?php

namespace frontend\controllers;

use frontend\models\Article;
use frontend\models\Like;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\data\Pagination;
use frontend\models\CommentForm;
use frontend\models\Comment;

//ДОБАВИЛИ!
/** @var yii\bootstrap5\ActiveForm $form */
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        // $model = new SignupForm();
        // if ($model->load(Yii::$app->request->post()) && $model->signup()) {
        //     Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
        //     return $this->goHome();
        // }

        // return $this->render('signup', [
        //     'model' => $model,
        // ]);


        //ДОБАВИЛИ
        $model = new SignupForm();
    
        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->validate() && $model->signup()){
                Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.'); 
                return $this->redirect('?r=site/login');
             } 

        }

            return $this->render('signup', [
                'model' => $model,
            ]);


    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }








    public function actionArticles()
    {
        $category_id = Yii::$app->request->get('category_id');
        $articles = Article::find()->orderBy('id');
        if($category_id){
            $articles=Article::find()->where(['category_id'=>$category_id]);
        }

        $pagination=new Pagination([
            'defaultPageSize'=>6,
            'totalCount'=>$articles->count()
        ]);

        $articles=$articles->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('articles',['articles'=>$articles, 'pagination'=>$pagination]);
    }

    public function actionArticle()
    {
        $id = Yii::$app->request->get('id');
        $article = Article::findOne($id);
        $comments=$article->comment;
        $commentForm=new CommentForm();


        $commentModel = new Comment();

        if ($commentModel->load(Yii::$app->request->post()))
        {
            $commentModel->article_id=$id;
            $commentModel->user_id=Yii::$app->user->identity->Id;
            if($commentModel->save()){
                $commentModel = new Comment(); //reset model
            }

        }

        $searchModel = new SearchComment();
        $dataProvider=$searchModel->search(['SearchComment'=>['article_id'=>$id]]);

        $user_id = Yii::$app->user->identity->Id;

        $like = Like::findOne(['article_id' => $id, 'user_id' => $user_id]);

        if ($like) {
            $likeBeginState = 'toggled-on';
        }
        else{
            $likeBeginState = 'toggled-off';
        }

        return $this->render('article',['article'=>$article,'comments'=>$comments,'commentModel'=>$commentModel,'searchModel'=>$searchModel, 'dataProvider'=>$dataProvider, 'likeBeginState'=>$likeBeginState]);
    }

    public function actionComment($article_id)
    {
        $user_id = Yii::$app->user->identity->id;
        $form = new CommentForm();
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $commentModel = new Comment();
                $commentModel->content = $form->comment;
                $commentModel->article_id = $article_id;
                $commentModel->user_id = $user_id;

                if ($commentModel->save()) {
                    /*
                    return $data = [
                        'success' => true,
                        'comment' => $form->comment,
                    ];
                    */

                }
            }
        }
    }

    public function actionGetlikestate(){
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $article_id = $data['article_id'];
            $user_id = Yii::$app->user->identity->Id;

            $like = Like::findOne(['article_id' => $article_id, 'user_id' => $user_id]);

            if ($like) {
                $state = 'toggled-on';
            }
            else{
                $state = 'toggled-off';
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return[
                'state'=>$state
            ];
        }
    }

    public function actionTogglelike()
    {

        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $article_id= $data['article_id'];
            $user_id = Yii::$app->user->identity->Id;

            $like=Like::findOne(['article_id'=>$article_id,'user_id'=>$user_id]);
            $message='like on';
            $class_name='toggled-on';

            if($like){
                $message='there is like';
                $class_name='toggled-off';
                $like->delete();
            }
            else{
                $message='there is no like';
                $class_name='toggled-on';
                $new_like = new Like();
                $new_like->article_id = $article_id;
                $new_like->user_id = $user_id;
                $new_like->save();
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'message' => $message,
                'state'=>$class_name,
                'code' => 100,
            ];
        }
    }
}
