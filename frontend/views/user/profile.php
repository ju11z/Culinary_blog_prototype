<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="row">
    <div class="col-6">
        <h3>Лайкнутые посты</h3>
            <?php foreach ($likedArticles as $article){
        ?>
        <div class="col-12">
            <!-- Blog post-->
            <div class="card mb-4">
                <a href="#!"><img class="card-img-top" src="<?=$article->image ?>" alt="..." /></a>
                <div class="card-body">
                    <div class="small text-muted">January 1, 2023</div>
                    <h2 class="card-title h4"><?=$article->title ?></h2>
                    <p class="card-text"><?=$article->descr ?></p>
                    <?php
                    $url = Url::toRoute(['site/article', 'id' => $article->id]);
                    ?>
                    <a class="btn btn-primary" href="<?= $url ?>">Read more →</a>
                </div>
            </div>

        </div>

        <?php }?>
    </div>
    <div class="col-6">
        <h3>Прокомментированные посты</h3>
        <?php foreach ($commentedArticles as $article){
            ?>
            <div class="col-12">
                <!-- Blog post-->
                <div class="card mb-4">
                    <a href="#!"><img class="card-img-top" src="<?=$article->image ?>" alt="..." /></a>
                    <div class="card-body">
                        <div class="small text-muted">January 1, 2023</div>
                        <h2 class="card-title h4"><?=$article->title ?></h2>
                        <p class="card-text"><?=$article->descr ?></p>
                        <?php
                        $url = Url::toRoute(['site/article', 'id' => $article->id]);
                        ?>
                        <a class="btn btn-primary" href="<?= $url ?>">Read more →</a>
                    </div>
                </div>

            </div>

        <?php }?>
    </div>
</div>
<?php
