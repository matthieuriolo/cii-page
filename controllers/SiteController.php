<?php

namespace app\modules\page\controllers;

use Yii;
use app\modules\page\models\Site;


use cii\web\Controller;
use cii\web\SecurityException;


use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;;


use app\modules\cii\Permission;

class SiteController extends Controller {
    
    public function behaviors() {
        return parent::behaviors() + [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function getAccessRoles() {
        return [Permission::MANAGE_CONTENT];
    }


    public function actionView() {
        $content = Yii::$app->seo->getModel()->content->outbox();
        
        return $this->render('view', [
            'model' => $content,
            'position' => ''
        ]);
    }

    public function isVisibleInShadow($content) {
        return true;
    }

    public function shadow($content, $position = null) {
        return $this->renderShadow('view', [
            'model' => $content,
            'position' => $position
        ]);
    }

    public function getLazyLabel() {
        return '<i class="glyphicon glyphicon-font"></i> ' . Yii::p('page', 'Text Content');
    }

    public function getLazyCreate($model = null, $form = null) {
        if(!$model) {
            $model = new Site();
        }

        return $this->getLazyForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyUpdate($model, $form) {
        return $this->getLazyForm($model, $form);
    }

    protected function getLazyForm($model, $form) {
        return $this->renderAjax('_form_content', [
            'model' => $model,
            'form' => $form,
            'languages' => Yii::$app->cii->language->getLanguagesForDropdown()
        ]);
    }

    public function getLazyView($model) {
        return $this->renderAjax('_view_content', [
            'model' => $model,
        ]);
    }
}
