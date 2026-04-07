<?php

declare(strict_types=1);

require_once __DIR__ . '/task_service.php';

$tasks = get_tasks();
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task</title>
    <style>
        :root {
            --bg-start: #263b7f;
            --bg-end: #7554a3;
            --panel: #ffffff;
            --text: #172b4d;
            --ok: #216e4e;
            --danger: #842029;
        }
        body { margin: 0; font-family: "Segoe UI", Tahoma, sans-serif; color: var(--text); background: linear-gradient(135deg, var(--bg-start), var(--bg-end)); min-height: 100vh; }
        .topbar { padding: 14px 18px; background: rgba(12, 18, 45, 0.35); color: #fff; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        .topbar h1 { margin: 0; font-size: 1.2rem; letter-spacing: 0.2px; }
        .chip { padding: 6px 10px; border-radius: 999px; background: rgba(255, 255, 255, 0.2); font-size: 0.85rem; }
        .wrap { padding: 0 16px 16px; }
        .nav { margin: 16px 0; border-radius: 12px; background: rgba(255, 255, 255, 0.13); backdrop-filter: blur(4px); padding: 14px; display: flex; gap: 10px; flex-wrap: wrap; }
        .nav a { text-decoration: none; color: var(--text); background: #fff; padding: 10px 12px; border-radius: 8px; font-weight: 600; }
        .nav a.active { background: #0f766e; color: #fff; }
        .box { max-width: 1100px; margin: 0 auto; background: var(--panel); border-radius: 12px; padding: 14px; box-shadow: 0 10px 26px rgba(9, 30, 66, 0.2); }
        h1 { margin-top: 0; }
        .msg, .err { margin-bottom: 12px; padding: 10px 12px; border-radius: 8px; background: #f8fafc; font-weight: 600; }
        .msg { color: var(--ok); }
        .err { color: var(--danger); }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px; text-align: left; vertical-align: top; }
        input, select { width: 100%; padding: 8px; margin-bottom: 8px; }
        button { border: 0; border-radius: 7px; padding: 8px 10px; color: #fff; background: #2563eb; cursor: pointer; font-weight: 600; }
        @media (max-width: 900px) {
            table, thead, tbody, tr, th, td { display: block; }
            th { font-weight: 700; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <h1>SISTEM MANAJEMEN TASK - AGILE BOARD</h1>
        <span class="chip">Menu: Update</span>
        <span class="chip">Mode: Trello Style</span>
    </header>

    <main class="wrap">
        <nav class="nav">
            <a href="index.php">Board</a>
            <a href="create_frontend.php">Create Task</a>
            <a class="active" href="update_frontend.php">Update Task</a>
            <a href="delete_frontend.php">Delete Task</a>
        </nav>

        <section class="box">
            <h1>Update Task</h1>
            <?php if ($message !== ''): ?>
                <p class="msg">Success: <?= e($message) ?></p>
            <?php endif; ?>
            <?php if ($error !== ''): ?>
                <p class="err">Error: <?= e($error) ?></p>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Form Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= (int) $task['id'] ?></td>
                            <td><?= e((string) $task['title']) ?></td>
                            <td><?= e((string) $task['status']) ?></td>
                            <td>
                                <form method="post" action="update_backend.php">
                                    <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">
                                    <input name="title" value="<?= e((string) $task['title']) ?>" required>
                                    <select name="status">
                                        <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>To Do</option>
                                        <option value="in-progress" <?= $task['status'] === 'in-progress' ? 'selected' : '' ?>>Doing</option>
                                        <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>Done</option>
                                    </select>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>