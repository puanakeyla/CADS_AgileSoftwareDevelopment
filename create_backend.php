<?php

declare(strict_types=1);

require_once __DIR__ . '/task_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$title = $_POST['title'] ?? '';
$status = $_POST['status'] ?? 'todo';

try {
    create_task($title, $status);
} catch (Throwable $e) {
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}

header('Location: index.php?message=' . urlencode('Task berhasil dibuat.'));
exit;