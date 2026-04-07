<?php

declare(strict_types=1);

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
    <title>Create Task - Project GitHub</title>
    <style>
        :root {
            --bg-start: #263b7f;
            --bg-end: #7554a3;
            --panel: #ffffff;
            --text: #172b4d;
            --primary: #0f766e;
            --primary-hover: #115e59;
            --danger: #842029;
            --ok: #216e4e;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: linear-gradient(135deg, var(--bg-start), var(--bg-end));
        }

        .topbar {
            padding: 14px 18px;
            background: rgba(12, 18, 45, 0.35);
            color: #fff;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .topbar h1 {
            margin: 0;
            font-size: 1.2rem;
            letter-spacing: 0.2px;
        }

        .chip {
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
            font-size: 0.85rem;
        }

        .page {
            padding: 0 16px 16px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--panel);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 10px 26px rgba(9, 30, 66, 0.2);
        }

        .navbar {
            margin: 16px 0;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.13);
            backdrop-filter: blur(4px);
            padding: 14px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .navbar a {
            border: 0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.95rem;
            text-decoration: none;
            background: #fff;
            color: var(--text);
            font-weight: 600;
        }

        .navbar a.active {
            background: #0f766e;
            color: #fff;
        }

        .header {
            margin-bottom: 14px;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 1.5rem;
        }

        .header p {
            margin: 0;
            color: #44546f;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        input,
        select {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            margin-bottom: 14px;
            font-size: 1rem;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        button,
        .back-link {
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.95rem;
            text-decoration: none;
            cursor: pointer;
        }
        button {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
        }
        button:hover { background: var(--primary-hover); }
        .back-link {
            background: #e2e8f0;
            color: #1e293b;
        }

        .msg,
        .err {
            margin: 0 0 12px;
            padding: 10px 12px;
            border-radius: 8px;
            font-weight: 600;
            background: #f8fafc;
        }

        .msg { color: var(--ok); }
        .err { color: var(--danger); }
    </style>
</head>
<body>
    <header class="topbar">
        <h1>SISTEM MANAJEMEN TASK - AGILE BOARD</h1>
        <span class="chip">Menu: Create</span>
        <span class="chip">Mode: Trello Style</span>
    </header>

    <main class="page">
        <nav class="navbar">
                <a href="index.php">Board</a>
            <a class="active" href="create_frontend.php">Create Task</a>
            <a href="update_frontend.php">Update Task</a>
            <a href="delete_frontend.php">Delete Task</a>
        </nav>

        <section class="container">
            <header class="header">
                <h1>Create Task</h1>
                <p>Tambahkan task baru dengan tampilan form yang konsisten dengan board.</p>
            </header>

            <?php if ($message !== ''): ?>
                <p class="msg"><?= e($message) ?></p>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <p class="err"><?= e($error) ?></p>
            <?php endif; ?>

            <form method="post" action="create_backend.php">
                <label for="title">Judul Task</label>
                <input id="title" name="title" placeholder="Contoh: Setup unit testing" required>

                <label for="status">Status Awal</label>
                <select id="status" name="status">
                    <option value="todo">To Do</option>
                    <option value="in-progress">In Progress</option>
                    <option value="done">Done</option>
                </select>

                <div class="actions">
                    <button type="submit">Simpan Task</button>
                    <a class="back-link" href="index.php">Kembali ke Dashboard</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>