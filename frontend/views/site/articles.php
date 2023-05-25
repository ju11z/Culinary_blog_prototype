<h2>index</h2>
<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="row">

<?php foreach ($articles as $article){
    ?>

                        <div class="col-lg-6">
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
    <?php
    echo LinkPager::widget(['pagination'=>$pagination]);
    ?>
</div>

