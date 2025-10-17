<?php

namespace app\controllers;

use yii\web\Controller;
use yii\helpers\Json;
use OpenApi\Generator;

class SwaggerController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $openapi = (new Generator())->generate([\Yii::getAlias('@app/controllers'), \Yii::getAlias('@app/models')]);
        return Json::decode($openapi->toJson());
    }

    public function actionUi()
    {
        return $this->renderAjax('index');
    }
}