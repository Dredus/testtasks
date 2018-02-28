<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
?>
<div class="row">
    <div class="col-lg-5">

        <?php $formAdd = ActiveForm::begin(['id' => 'addnews-form']); ?>

            <?= $formAdd->field($form, 'ftitle')->textInput(['autofocus' => true]) ?>

            <?//= $form->field($model, 'email') ?>

            <?//= $form->field($model, 'subject') ?>

            <?= $formAdd->field($form, 'ftext')->textarea(['rows' => 6]) ?>

            <div class="form-group">
                <?//= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'addnews-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>            
<div class="container col-md-6">
	<?php  
	foreach ($news as $ns){?>
		<div class="row">
		  	<div class="col-md-12">
		  		<figure class="thumbnail featured-thumbnail col-md-3">
	  				<img src="<?=$ns->photo?>" alt="<?=$ns->title?>">
		  		</figure>
		  		<div class="col-md-9">
		         	<h5><a href="<?yii::$app->urlManager->createUrl(['site/news', 'title' => $ns->title]);?>" title="<?=$ns->title?>"><?=$ns->title?></a></h5>
	         		<div class="excerpt"><?=$ns->text?></div>
	         		<div class="clear"></div>
		  		</div>
		  	</div>
		</div>
	<?}?>
</div>
<?LinkPager::widget(['pagination'=>$pagination]);?>