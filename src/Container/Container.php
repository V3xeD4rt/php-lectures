<?php

namespace App\Container;

use App\Repository\{
    TaskRepositoryInterface,
    FileTaskRepository,
    InMemoryTaskRepository,
    MySqlTaskRepository
};
use App\Controller\TaskController;
use PDO;
use Exception;

class Container
{
    // Хранилище уже созданных объектов (Singleton внутри контейнера)
    private array $definitions = [];

    // Конфигурация проекта
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // Получение объекта по имени класса
    public function get(string $id): mixed
    {
        if (!isset($this->definitions[$id])) {
            $this->definitions[$id] = $this->create($id);
        }

        return $this->definitions[$id];
    }

    private function create(string $id)
    {
        switch ($id) {

            // Создание PDO
            case PDO::class:
                $db = $this->config['db'];
                return new PDO(
                    $db['dsn'],
                    $db['user'],
                    $db['pass'],
                    $db['options']
                );

            // Файловый репозиторий
            case FileTaskRepository::class:
                return new FileTaskRepository(
                    $this->config['storage']['file']
                );

            // MySQL репозиторий
            case MySqlTaskRepository::class:
                return new MySqlTaskRepository(
                    $this->get(PDO::class)
                );

            // In-memory (не обязателен, учебный)
            case InMemoryTaskRepository::class:
                return new InMemoryTaskRepository();

            // Выбор реализации интерфейса
            case TaskRepositoryInterface::class:
                $type = $_SESSION['repository_mode'] ?? $this->config['repository'];


                return match ($type) {
                    'file' => $this->get(FileTaskRepository::class),
                    'mysql' => $this->get(MySqlTaskRepository::class),
                    default => $this->get(MySqlTaskRepository::class),
                };

            // Контроллер
            case TaskController::class:
                return new TaskController(
                    $this->get(TaskRepositoryInterface::class)
                );

            default:
                throw new Exception("Неизвестный сервис: $id");
        }
    }
}
