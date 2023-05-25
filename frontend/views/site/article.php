<style>
    @-webkit-keyframes like {
        0%   { transform: scale(1); }
        90%   { transform: scale(1.2); }
        100% { transform: scale(1.1); }
    }
    ion-icon.toggled-on{
        animation:like 0.5s 1;
        fill:red;
        stroke:none;
    }
    ion-icon{
        font-size: 50px;
        fill:transparent;
        stroke:black;
        stroke-width:30;
        transition:all 0.5s;
    }
</style>
<?php
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use frontend\models\Comment;
use yii\helpers\Url;
?>
    <h2></h2>
    <h3></h3>
    <div></div>
    <article>

        <!-- Post header-->
        <header class="mb-4">
            <!-- Post title-->
            <div class="row">
                <div class="col-10">
                    <h1 class="fw-bolder mb-1"><?= $article->title ?></h1>
                </div>
                <div class="col-1"></div>
                <div class="col-1">
                    <div class='large-font text-center top-20'>
                        <ion-icon name="heart">
                            <div class='red-bg'></div>
                        </ion-icon>
                    </div>
                </div>
            </div>

            <!-- Post meta content-->
            <div class="text-muted fst-italic mb-2">Posted on <?= $article->date_posted ?></div>
            <!-- Post categories-->
            <a class="badge bg-secondary text-decoration-none link-light" href="#!">Web Design</a>
            <a class="badge bg-secondary text-decoration-none link-light" href="#!">Freebies</a>
        </header>
        <!-- Preview image figure-->
        <?= Html::img(Yii::getAlias('@app').'\..\common\uploads\\'.$article->image);?>
        <img src="..\..\common\uploads\<?=$article->image?>"/>
        <!-- Post content-->
        <section class="mb-5">
            <?= $article->content ?>
        </section>
    </article>
    <!-- Comments section-->
<div class="comment-index">


    <!-- Render create form -->
    <?= $this->render('_commentForm', [
        'model' => $commentModel,
    ]) ?>


    <?php Pjax::begin(['id' => 'comments']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'login'=>'user.username',
            'content'
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    let icon = document.querySelector('ion-icon');

    function documentLoaded(){

        $.ajax({
            url: '?r=site/getlikestate',
            type: 'post',
            data: {article_id: <?= $article->id ?> },
            success: function (data) {
                if(data.state=='toggled-on'){
                    icon.classList.add('toggled-on');
                }
                else{
                    icon.classList.remove('toggled-on');
                }
            }
        });

    }

    documentLoaded();

    icon.onclick = function(){
        //icon.classList.toggle('toggled-on');

        $.ajax({
            url: '?r=site/togglelike',
            type: 'post',
            data: {article_id: <?= $article->id ?> },
            success: function (data) {
                if(data.state=='toggled-on'){
                    icon.classList.add('toggled-on');
                }
                else{
                    icon.classList.remove('toggled-on');
                }
            }

        });
    }
</script>
