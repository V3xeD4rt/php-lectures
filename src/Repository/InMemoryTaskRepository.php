<?php

namespace App\Repository;

use App\Model\Task;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    private array $tasks = [];

    public function __construct() {
        // Инициализируем начальные задачи
        $this->tasks = [
            new Task("Купить кофе", false, 1),
            new Task("Проспать пары", false, 2),
            new Task("Опоздать на пары", false, 3)
        ];
    }

    public function findAll(): array {
        return $this->tasks;
    }
    
    public function add($task): void { 
        // Для демонстрации добавляем новую задачу
        $newId = count($this->tasks) + 1;
        $newTask = new Task($task->getTitle(), false, $newId);
        $this->tasks[] = $newTask;
    }
    
    public function toggle(int $taskId): void {
        foreach ($this->tasks as $task) {
            if ($task->getId() == $taskId) {
                $task->setCompleted(!$task->isCompleted());
                break;
            }
        }
    }
    
    public function delete(int $taskId): void {
        $this->tasks = array_filter($this->tasks, function($task) use ($taskId) {
            return $task->getId() != $taskId;
        });
    }
}