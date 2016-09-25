<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'language_id',
            'format' => 'html',
            'value' => empty($model->language_id) ? null : Html::a($model->language->name, [Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), 'id' => $model->language->id]),
        	'visible' => Yii::$app->cii->package->setting('cii', 'multilanguage'),
        ],
    ],
]) ?>

<hr>

<?= $model->content; ?>