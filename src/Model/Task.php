<?php

namespace App\Model;

class Task
{
    // Идентификатор задачи
    private int $id;

    // Название задачи
    private string $title;

    // Флаг выполнения
    private bool $completed;

    public function __construct(
        string $title,
        bool $completed = false,
        int $id = 0
    ) {
        $this->title = $title;
        $this->completed = $completed;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }
    
    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }
}
