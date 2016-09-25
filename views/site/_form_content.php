<?php

use yii\helpers\Html;
use cii\widgets\Toggler;

if(Yii::$app->cii->package->setting('cii', 'multilanguage')) {
?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'language_id')->dropDownList($languages) ?>
    </div>
</div>

<hr>
<?php } ?>

<?= $form->field($model, 'content')->textarea(['rows' => 40, 'data-controller' => 'tinymce']) ?>
