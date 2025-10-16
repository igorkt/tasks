<?php

namespace app\repositories;

use app\models\Tasks;

interface TaskRepositoryInterface
{
    /**
     * @param int $id
     * @return null|Tasks
     */
    public function findById(int $id): ?Tasks;

    /**
     * @param array $criteria
     * @return array
     */
    public function findAll(array $criteria = []): array;

    /**
     * @param Tasks $task
     * @return bool
     */
    public function save(Tasks $task): bool;
    
    /**
     * @param Tasks $task
     * @return bool
     */
    public function delete(Tasks $task): bool;
}