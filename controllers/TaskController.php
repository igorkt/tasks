<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\services\TaskService;
use yii\base\InvalidArgumentException;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\db\Exception as DbException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct($id, $module, TaskService $taskService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->taskService = $taskService;
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex()
    {
        $criteria = \Yii::$app->request->get();
        $tasks = $this->taskService->list($criteria);
        return $tasks;
    }

    public function actionCreate()
    {
        $body = \Yii::$app->request->post();
        try {
            $task = $this->taskService->create($body);
            return $task;
        } catch (InvalidArgumentException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (DbException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function actionUpdate($id)
    {
        $body = \Yii::$app->request->post();
        try {
            $task = $this->taskService->update($id, $body);
            return $task;
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (DbException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        try {
            $this->taskService->delete($id);
            \Yii::$app->response->setStatusCode(204);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (DbException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}