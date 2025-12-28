<?php

namespace App\Repository;

use App\Model\Task;

class FileTaskRepository implements TaskRepositoryInterface
{
    private string $filepath;

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
            if (!is_array($item) || empty($item['title']) || !isset($item['id'])) {
                continue;
            }

            $tasks[] = new Task(
                $item['title'],
                (bool)($item['completed'] ?? false),
                (int)$item['id']
            );
        }

        usort($tasks, fn($a, $b) => $b->getId() - $a->getId());

        return $tasks;
    }

    public function add(Task $task): void
    {
        $tasks = $this->findAll();

        $maxId = 0;
        foreach ($tasks as $existingTask) {
            $maxId = max($maxId, $existingTask->getId());
        }

        $tasks[] = new Task(
            $task->getTitle(),
            $task->isCompleted(),
            $maxId + 1
        );

        $this->writeTasks($tasks);
    }

    public function toggle(int $taskId): void
    {
        $tasks = $this->findAll();

        foreach ($tasks as $task) {
            if ($task->getId() === $taskId) {
                $task->setCompleted(!$task->isCompleted());
                break;
            }
        }

        $this->writeTasks($tasks);
    }

    public function delete(int $taskId): void
    {
        $tasks = array_filter(
            $this->findAll(),
            fn($task) => $task->getId() !== $taskId
        );

        $this->writeTasks($tasks);
    }

    private function writeTasks(array $tasks): void
    {
        $data = [];
        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'completed' => $task->isCompleted(),
            ];
        }

        $fp = fopen($this->filepath, 'c+');
        if ($fp === false) {
            throw new \RuntimeException('Не удалось открыть файл хранения задач');
        }

        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite(
            $fp,
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
