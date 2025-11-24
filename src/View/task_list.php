<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Лист Задач</title>
</head>

<body>
    <div class="header">
        <h1>Список задач</h1>
        <?php if (!empty($tasks)): ?>
        <a href="?route=task/add" class="add-link">+ Добавить задачу</a>
        <?php endif?>
    </div>
    
    <?php if (empty($tasks)): ?>
        <div>
            <p>Задач нет. Добавить задачи</p>
            <a href="?route=task/add" class="add-link">+ Добавить задачу</a>
        </div>
    <?php else: ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <button <?= $task->isCompleted() ? 'completed' : '' ?> 
                            onclick="location.href='?route=task/toggle&id=<?= $task->getId() ?>'">
                        <?= $task->isCompleted() ? "✓" : "X" ?>
                    </button>
                    
                    <div>
                        <?= htmlspecialchars($task->getTitle()) ?>
                    </div>
                    
                    <div>
                            <?= $task->isCompleted() ? "Выполнено" : "Не выполнено" ?>
                        <button onclick="if(confirm('Удалить задачу?')) location.href='?route=task/delete&id=<?= $task->getId() ?>'">
                            Удалить
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>

</html>