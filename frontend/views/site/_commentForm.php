<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var frontend\models\Comment $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php

$this->registerJs(
    '$("document").ready(function(){ 
		$("#new_comment").on("pjax:end", function() {
			$.pjax.reload({container:"#comments"});  //Reload GridView
		});
    });'
);
?>

<div class="comment-form">

    <?php yii\widgets\Pjax::begin(['id' => 'new_comment','timeout' => 5000 ]) ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>




    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php yii\widgets\Pjax::end() ?>


</div>