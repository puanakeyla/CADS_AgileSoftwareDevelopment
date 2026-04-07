<?php

declare(strict_types=1);

const TASK_FILE = __DIR__ . '/tasks.json';

function read_storage(): array
{
    if (!file_exists(TASK_FILE)) {
        file_put_contents(TASK_FILE, json_encode([], JSON_PRETTY_PRINT));
    }

    $raw = file_get_contents(TASK_FILE);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);

    return is_array($data) ? $data : [];
}

function write_storage(array $tasks): void
{
    file_put_contents(TASK_FILE, json_encode(array_values($tasks), JSON_PRETTY_PRINT));
}
