<?php

declare(strict_types=1);

require_once __DIR__ . '/task_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: index.php');
	exit;
}

$id = (int) ($_POST['id'] ?? 0);
$title = trim((string) ($_POST['title'] ?? ''));
$status = (string) ($_POST['status'] ?? 'todo');

if ($title === '') {
	$tasks = get_tasks();
	foreach ($tasks as $task) {
		if ((int) ($task['id'] ?? 0) === $id) {
			$title = (string) ($task['title'] ?? '');
			break;
		}
	}
}

try {
	$updated = update_task($id, $title, $status);
} catch (Throwable $e) {
	if (strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest') {
		http_response_code(400);
		header('Content-Type: application/json');
		echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
		exit;
	}

	header('Location: update_frontend.php?error=' . urlencode($e->getMessage()));
	exit;
}

if (strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest') {
	if (!$updated) {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode(['ok' => false, 'error' => 'Task tidak ditemukan atau status tidak valid.']);
		exit;
	}

	header('Content-Type: application/json');
	echo json_encode(['ok' => true]);
	exit;
}

if (!$updated) {
	header('Location: update_frontend.php?error=' . urlencode('Task tidak ditemukan atau status tidak valid.'));
	exit;
}

header('Location: update_frontend.php?message=' . urlencode('Task berhasil diupdate.'));
exit;
