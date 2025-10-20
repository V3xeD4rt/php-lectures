<?php

namespace App\Repository;

use App\Model\Task;
class FileTaskRepository implements TaskRepositoryInterface
{

    public function findAll(): array{
        return [
            new Task("Купить кофе"),
            new Task("Проспать пары"),
            new Task("Опоздать на пары")
        ];
    }
    public function add( $task): void{ 


    }
}