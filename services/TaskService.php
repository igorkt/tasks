<?php

namespace app\services;

use app\models\Tasks;
use app\repositories\TaskRepositoryInterface;
use app\exceptions\NotFoundException;
use yii\base\InvalidArgumentException;
use yii\db\Exception as DbException;

class TaskService
{
    private TaskRepositoryInterface $taskRepository;

    /**
     * @param TaskRepositoryInterface $taskRepository
     * @return void
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param array $data
     * @return Tasks
     * @throws InvalidArgumentException
     * @throws DbException
     */
    public function create(array $data)
    {
        $task = new Tasks();
        $task->load($data, '');
        if (!$task->validate()) {
            throw new InvalidArgumentException('Validation failed: ' . json_encode($task->getErrors()));
        }

        if (!$this->taskRepository->save($task)) {
            throw new DbException('Saving failed');
        }

        return $task;
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function list(array $criteria = []): array
    {
        return $this->taskRepository->findAll($criteria);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Tasks
     * @throws NotFoundException
     * @throws InvalidArgumentException
     * @throws DbException
     */
    public function update(int $id, array $data)
    {
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            throw new NotFoundException("Task #{$id} not found");
        }

        $task->load($data, '');
        if (!$task->validate()) {
            throw new InvalidArgumentException('Validation failed: ' . json_encode($task->getErrors()));
        }

        if (!$this->taskRepository->save($task)) {
            throw new DbException('Saving failed');
        }

        return $task;
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundException
     * @throws DbException
     */
    public function delete(int $id)
    {
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            throw new NotFoundException("Task #{$id} not found");
        }

        if (!$this->taskRepository->delete($task)) {
            throw new DbException('Saving failed');
        }
    }
}