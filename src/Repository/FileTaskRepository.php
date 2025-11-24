<?php

namespace App\Repository;

use App\Model\Task;

class FileTaskRepository implements TaskRepositoryInterface
{   
    public string $filepath;
    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }
    
    public function findAll(): array
    {
        if (!file_exists($this->filepath)) {
            return [];
        }
        $content = file_get_contents($this->filepath);
        if ($content === false || trim($content) === '') {
            return [];
        }
        $data = json_decode($content, true);
        if (!is_array($data)) {
            return [];
        }
        $tasks = [];
        foreach ($data as $item) {
            $tasks[] = new Task(
                $item['title'] ?? '',
                $item['completed'] ?? false,
                $item['id'] ?? null
            );
        }
        return $tasks;
    }
    
    public function add($task): void
    { 
        $tasks = $this->findAll();
        
        // Находим максимальный ID
        $maxId = 0;
        foreach ($tasks as $existingTask) {
            if ($existingTask->getId() > $maxId) {
                $maxId = $existingTask->getId();
            }
        }
        
        $tasks[] = [ 
            'id' => $maxId + 1,
            'title' => $task->getTitle(),
            'completed' => $task->isCompleted()
        ];
        
        file_put_contents($this->filepath, json_encode($tasks, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    public function toggle(int $taskId): void
    {
        $tasks = $this->findAll();
        $data = [];
        
        foreach ($tasks as $task) {
            $item = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'completed' => $task->isCompleted()
            ];
            
            // Переключаем статус для найденной задачи
            if ($task->getId() == $taskId) {
                $item['completed'] = !$task->isCompleted();
            }
            
            $data[] = $item;
        }
        
        file_put_contents($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    public function delete(int $taskId): void
    {
        $tasks = $this->findAll();
        $data = [];
        
        foreach ($tasks as $task) {
            // Пропускаем задачу, которую нужно удалить
            if ($task->getId() == $taskId) {
                continue;
            }
            
            $data[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'completed' => $task->isCompleted()
            ];
        }
        
        file_put_contents($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}