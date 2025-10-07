<?php

namespace app\controllers;

use app\models\Tasks;
use yii\base\DynamicModel;
use yii\rest\ActiveController;

class TaskController extends ActiveController
{
    public $modelClass = 'app\models\Tasks';

    public function actions()
    {
        $actions = parent::actions();

        $possibleStatuses = array_keys(Tasks::statuses());

        $actions['index']['dataFilter'] = [
            'class' => \yii\data\ActiveDataFilter::class,
            'searchModel' => (
                new DynamicModel(['status']))
                ->addRule('status', 'string')
                ->addRule('status', 'in', [
                    'range' => $possibleStatuses,
                    'message' => '{attribute} must be one of this values: ' . implode(', ', $possibleStatuses)
                ]),
        ];

        return $actions;
    }
}