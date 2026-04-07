<?php

declare(strict_types=1);

require_once __DIR__ . '/task_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$deleted = delete_task($id);

if (!$deleted) {
    header('Location: ../index.php?error=' . urlencode('Task tidak ditemukan.'));
    exit;
}

header('Location: ../index.php?message=' . urlencode('Task berhasil dihapus.'));
exit;