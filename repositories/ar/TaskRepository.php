<?php

namespace app\repositories\ar;

use app\models\Tasks;
use app\repositories\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @param int $id
     * @return null|Tasks
     */
    public function findById(int $id): ?Tasks
    {
        return Tasks::findOne($id);
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function findAll(array $criteria = []): array
    {
        $query = Tasks::find();
        foreach ($criteria as $field => $value) {
            $query->andWhere([$field => $value]);
        }
        return $query->all();
    }

    /**
     * @param Tasks $task
     * @return bool
     */
    public function save(Tasks $task): bool
    {
        return $task->save();
    }

    /**
     * @param Tasks $task
     * @return bool
     */
    public function delete(Tasks $task): bool
    {
        return (bool)$task->delete();
    }
}