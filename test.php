<?php

declare(strict_types=1);

require_once __DIR__ . '/crud/task_service.php';

$tests = 0;
$passed = 0;
$backup = file_exists(TASK_FILE) ? file_get_contents(TASK_FILE) : '[]';

function assert_true(bool $condition, string $message): void
{
    global $tests, $passed;
    $tests++;

    if ($condition) {
        $passed++;
        echo "[PASS] {$message}" . PHP_EOL;
        return;
    }

    echo "[FAIL] {$message}" . PHP_EOL;
}

write_storage([]);

try {
    $task = create_task('Implement login', 'todo');
    assert_true($task['id'] === 1, 'create_task menghasilkan id 1');

    $tasks = get_tasks();
    assert_true(count($tasks) === 1, 'get_tasks mengembalikan 1 item');

    $updated = update_task(1, 'Implement login v2', 'in-progress');
    assert_true($updated, 'update_task berhasil untuk id valid');

    $tasks = get_tasks();
    assert_true($tasks[0]['title'] === 'Implement login v2', 'judul task terupdate');
    assert_true($tasks[0]['status'] === 'in-progress', 'status task terupdate');

    $deleted = delete_task(1);
    assert_true($deleted, 'delete_task berhasil untuk id valid');
    assert_true(count(get_tasks()) === 0, 'data kosong setelah delete');

    $failedUpdate = update_task(99, 'Unknown', 'todo');
    assert_true($failedUpdate === false, 'update_task false untuk id tidak ada');

    $failedDelete = delete_task(99);
    assert_true($failedDelete === false, 'delete_task false untuk id tidak ada');
} finally {
    file_put_contents(TASK_FILE, (string) $backup);
}

echo PHP_EOL . "Result: {$passed}/{$tests} test passed" . PHP_EOL;
exit($tests === $passed ? 0 : 1);
