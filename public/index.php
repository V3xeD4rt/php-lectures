<?php

session_start();

// Подключаем автозагрузчик Composer.
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\TaskController;
use App\Container\Container;

// Если параметр route не передан — показываем список задач.
$route = $_GET['route'] ?? 'task/list';

// Загружаем конфигурацию проекта
$config = require __DIR__ . '/../src/Model/config.php';

$container = new Container($config);

// Получаем контроллер через контейнер.
$controller = $container->get(TaskController::class);

switch ($route) {
    case 'task/list':
        $controller->list();
        break;

    case 'task/add':
        $controller->add();
        break;

    case 'task/toggle':
        $controller->toggle();
        break;

    case 'task/delete':
        $controller->delete();
        break;

    case 'task/switch-mode':
        $controller->switchMode();
        break;

    default:
        http_response_code(404);
        echo '404 not found';
}
