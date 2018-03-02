<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;

print_r($newss);
?>
<div class="row">
	<a class="addnews btn btn-primary" href="#">Добавить новость</a>
    <div class="col-lg-5 addnewsform">

        <?php $formAdd = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],'id' => 'addnews-form']); ?>

            <?= $formAdd->field($form, 'ftitle')->textInput(['autofocus' => true])->label('Название новости') ?>

            <?= $formAdd->field($form, 'file')->fileInput()->label('Фотография новости')?>

            <?= $formAdd->field($form, 'ftext')->textarea(['rows' => 6])->label('Текст новости') ?>

            <div class="form-group">
                <?= Html::submitButton('Добавить новость', ['class' => 'btn btn-primary', 'name' => 'addnews-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>            
<div class="container col-md-6">
	<?php  
	foreach ($news as $ns){?>
		<div class="row news<?=$ns->id?>">
		  	<div class="col-md-12">
		  		<figure class="thumbnail featured-thumbnail col-md-3">
	  				<img src="<?=$ns->photo?>" alt="<?=$ns->title?>">
		  		</figure>
		  		<div class="col-md-9">
		         	<h5><a href="<?yii::$app->urlManager->createUrl(['site/news', 'title' => $ns->title]);?>" title="<?=$ns->title?>"><?=$ns->title?></a></h5>
	         		<div class="excerpt"><?=\yii\helpers\StringHelper::truncate($ns->text,200,'...')?></div>
	         		<a class="chnews" href="#" rel="<?=$ns->id?>">Изменить</a>
	         		<a class="delnews" href="#" rel="<?=$ns->id?>">Удалить</a>
	         		<div class="clear"></div>
		  		</div>
		  	</div>
		</div>
	<?}?>
</div>
<?php
	yii\bootstrap\Modal::begin([
	    'headerOptions' => ['id' => 'myModal'],
	    'id' => 'modal',
	    'size' => 'modal-md',
	    //keeps from closing modal with esc key or by clicking out of the modal.
	    // user must click cancel or X to close
	    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
	]);
 	$formUpd = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],'id' => 'updnews-form']); ?>

    <?= $formUpd->field($formp, 'utitle')->textInput(['autofocus' => true])->label('Название новости') ?>

    <img src="" width="100" height="100">

    <?= $formUpd->field($formp, 'ufile')->fileInput()->label('Фотография новости')?>

    <input type="hidden" id="addnewsform-fid" value="" class="form-control" name="UpdNewsForm[uid]">

    <?= $formUpd->field($formp, 'utext')->textarea(['rows' => 6])->label('Текст новости') ?>

    <div class="form-group">
        <?= Html::submitButton('Обновить новость', ['class' => 'btn btn-primary', 'name' => 'addnews-button']) ?>
    </div>

<?php 
	ActiveForm::end();
	yii\bootstrap\Modal::end();
?>
<?LinkPager::widget(['pagination'=>$pagination]);?>