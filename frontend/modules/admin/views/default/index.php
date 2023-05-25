<?php
use yii\helpers\Html;
?>

<div class="admin-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
    <p> <?= Html::a(Yii::t('app', 'Articles'), ['article/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Categories'), ['category/index'], ['class' => 'btn btn-primary']) ?>
        </p>
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
