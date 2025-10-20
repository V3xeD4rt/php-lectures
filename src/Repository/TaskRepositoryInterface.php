<?php

namespace App\Repository;

interface TaskRepositoryInterface
{
    public function findAll(): array;
    public function add(App\Model\Task $task): void;
}