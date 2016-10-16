<?php

use yii\helpers\Html;
use cii\widgets\Toggler;
use cii\widgets\BrowserModal;
use cii\helpers\FileHelper;

if(Yii::$app->cii->package->setting('cii', 'multilanguage')) {
?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'language_id')->dropDownList($languages) ?>
    </div>
</div>

<hr>
<?php } ?>

<?= BrowserModal::widget([
    'id' => $imageId = uniqid(),
    'mimeTypes' => FileHelper::$imageMimeTypes,
]) ?>

<?= BrowserModal::widget([
    'id' => $mediaId = uniqid(),
    'mimeTypes' => FileHelper::$videoMimeTypes,
]) ?>

<?= BrowserModal::widget([
    'id' => $fileId = uniqid(),
]) ?>

<?= $form->field($model, 'content')->textarea([
	'rows' => 40,
	'data-controller' => 'tinymce',
	'data-browser-image' => $imageId,
	'data-browser-file' => $fileId,
	'data-browser-media' => $mediaId,
])->label(false) ?>
