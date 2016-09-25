<?php

namespace app\modules\page\models;

use Yii;
use yii\helpers\VarDumper;

use app\modules\cii\base\LazyContentModel;
use app\modules\cii\base\ContentInterface;

use app\modules\cii\models\Language;
use app\modules\cii\models\Content;

class Site extends LazyContentModel implements ContentInterface {
    public static $lazyControllerRoute = 'page/site';
    public $canBeShadowed = true;
    
    public static function tableName() {
        return '{{%Page_SiteContent}}';
    }

    public function rules() {
        return [
            [['content_id'], 'required'],
            [['content_id'], 'integer'],
            
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],

            [['content'], 'string'],
        ];
    }


    public function replaceSubcontents($position) {
        $content = $this->content;

        $matches = [];
        $pattern = '/\{\s*content\s+name\s*:\s*\"([^\"]+)\"\s*\}/';
        $offset = 0;
        while(preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE, $offset)) {
            $offset = $matches[0][1] + strlen($matches[0][0]);
            $model = Content::find()
                ->where([
                    'name' => $matches[1][0],
                    'enabled' => true
                ])
                ->one();

            $result = '';
            if($model) {
                try {
                    $model = $model->outbox();
                    $info = $model->getShadowInformation();
                    $controller = Yii::$app->createController($info['route'])[0];
                    return $controller->$info['action']($model, $position);
                }catch(\Exception $e) {
                    if(YII_DEBUG) {
                        $result = VarDumper::dump($e);
                    }
                }
            }

            $content = preg_replace($pattern, $result, $content, 1);
        }

        return $content;
    }

    public function getShadowInformation() {
        return [
            'route' => 'page/site',
            'isVisible' => 'isVisibleInShadow',
            'action' => 'shadow',  
        ];
    }

    static public function getTypename() {
      return 'Page:Text Site';
    }

    public function forwardToController($controller) {
        return Yii::$app->runAction('page/site/view', Yii::$app->request->queryParams);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'language_id' => Yii::t('app', 'Language'),
            'content' => Yii::t('app', 'Site content'),
        ];
    }
}
