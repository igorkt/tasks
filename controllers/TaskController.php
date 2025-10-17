<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\models\Tasks;
use app\services\TaskService;
use yii\base\InvalidArgumentException;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\db\Exception as DbException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use OpenApi\Attributes as OA;

#[OA\Info(title: "Api for working with tasks. Simple CRUD operations.", version: "1.0.0")]
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

    #[OA\Get(
        path: '/tasks',
        summary: 'Get list of all tasks',
        tags: ["Tasks"]
    )]
    #[OA\Parameter(
        name: "status",
        in: "query",
        required: false,
        description: "Filter task status",
        schema: new OA\Schema("#/components/schemas/TaskStatus")
    )]
    #[OA\Response(
        response: 200,
        description: "Successful response",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/Task")
        )
    )]
    public function actionIndex()
    {
        $criteria = \Yii::$app->request->get();
        $tasks = $this->taskService->list($criteria);
        return $tasks;
    }

    #[OA\Post(
        path: '/tasks',
        summary: 'Add new task',
        tags: ["Tasks"]
    )]
    #[OA\RequestBody(
        required: true,
        description: "Task data in JSON format",
        content: new OA\JsonContent(
            type: "object",
            ref: "#/components/schemas/TaskAdd"
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Successful response",
        content: new OA\JsonContent(
            type: "object",
            ref: "#/components/schemas/Task"
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Validation error",
        content: new OA\JsonContent(ref: "#/components/schemas/ValidationFailedResponse")
    )]
    #[OA\Response(
        response: 500,
        description: "Server error",
        content: new OA\JsonContent(ref: "#/components/schemas/ServerError")
    )]
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

    #[OA\Put(
        path: '/tasks/{id}',
        summary: 'Update task data',
        tags: ["Tasks"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        description: "Unique id of task",
        required: true,
        schema: new OA\Schema("#/components/schemas/TaskId")
    )]
    #[OA\RequestBody(
        required: false,
        description: "Task data in JSON format",
        content: new OA\JsonContent(
            type: "object",
            ref: "#/components/schemas/TaskUpdate"
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Successful response",
        content: new OA\JsonContent(
            type: "object",
            ref: "#/components/schemas/Task"
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Validation error",
        content: new OA\JsonContent(ref: "#/components/schemas/ValidationFailedResponse")
    )]
    #[OA\Response(
        response: 404,
        description: "Not found error",
        content: new OA\JsonContent(ref: "#/components/schemas/NotFoundResponse")
    )]
    #[OA\Response(
        response: 500,
        description: "Server error",
        content: new OA\JsonContent(ref: "#/components/schemas/ServerError")
    )]
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

    #[OA\Delete(
        path: '/tasks/{id}',
        summary: 'Delete task',
        tags: ["Tasks"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        description: "Unique id of task",
        required: true,
        schema: new OA\Schema("#/components/schemas/TaskId")
    )]
    #[OA\Response(
        response: 204,
        description: "Successful response",
    )]
    #[OA\Response(
        response: 422,
        description: "Validation error",
        content: new OA\JsonContent(ref: "#/components/schemas/ValidationFailedResponse")
    )]
    #[OA\Response(
        response: 404,
        description: "Not found error",
        content: new OA\JsonContent(ref: "#/components/schemas/NotFoundResponse")
    )]
    #[OA\Response(
        response: 500,
        description: "Server error",
        content: new OA\JsonContent(ref: "#/components/schemas/ServerError")
    )]
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