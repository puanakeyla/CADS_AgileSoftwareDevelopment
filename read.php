<?php

declare(strict_types=1);

require_once __DIR__ . '/task_service.php';

header('Content-Type: application/json');
echo json_encode(get_tasks(), JSON_PRETTY_PRINT);
