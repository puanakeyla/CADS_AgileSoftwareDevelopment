<?php

declare(strict_types=1);

require_once __DIR__ . '/storage.php';

function get_tasks(): array
{
    return read_storage();
}

function create_task(string $title, string $status = 'todo'): array
{
    $title = trim($title);
    if ($title === '') {
        throw new InvalidArgumentException('Title wajib diisi.');
    }

    $allowed = ['todo', 'in-progress', 'done'];
    if (!in_array($status, $allowed, true)) {
        $status = 'todo';
    }

    $tasks = read_storage();
    $nextId = empty($tasks) ? 1 : (max(array_column($tasks, 'id')) + 1);

    $newTask = [
        'id' => $nextId,
        'title' => $title,
        'status' => $status,
    ];

    $tasks[] = $newTask;
    write_storage($tasks);

    return $newTask;
}

function update_task(int $id, string $title, string $status): bool
{
    $title = trim($title);
    if ($title === '') {
        throw new InvalidArgumentException('Title wajib diisi.');
    }

    $allowed = ['todo', 'in-progress', 'done'];
    if (!in_array($status, $allowed, true)) {
        return false;
    }

    $tasks = read_storage();
    foreach ($tasks as &$task) {
        if ((int) $task['id'] === $id) {
            $task['title'] = $title;
            $task['status'] = $status;
            write_storage($tasks);
            return true;
        }
    }

    return false;
}

function delete_task(int $id): bool
{
    $tasks = read_storage();
    $before = count($tasks);

    $tasks = array_values(array_filter($tasks, static function ($task) use ($id): bool {
        return (int) $task['id'] !== $id;
    }));

    if (count($tasks) === $before) {
        return false;
    }

    write_storage($tasks);
    return true;
}
