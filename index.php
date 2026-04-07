<?php

declare(strict_types=1);

require_once __DIR__ . '/task_service.php';

$tasks = get_tasks();
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

$groupedTasks = [
    'todo' => [],
    'in-progress' => [],
    'done' => [],
];

foreach ($tasks as $task) {
    $status = (string) ($task['status'] ?? 'todo');
    if (!array_key_exists($status, $groupedTasks)) {
        $status = 'todo';
    }
    $groupedTasks[$status][] = $task;
}

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
    <title>Board Project Management</title>
    <style>
        :root {
            --bg-start: #263b7f;
            --bg-end: #7554a3;
            --panel: #f4f5f7;
            --card: #ffffff;
            --text: #172b4d;
            --muted: #5e6c84;
            --ok: #216e4e;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: linear-gradient(135deg, var(--bg-start), var(--bg-end));
            min-height: 100vh;
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

        .navbar {
            margin: 16px;
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

        .msg,
        .err {
            margin: 0 16px 10px;
            padding: 10px 12px;
            border-radius: 8px;
            font-weight: 600;
            background: #fff;
        }

        .msg { color: var(--ok); }
        .err { color: #842029; }

        .board {
            display: grid;
            grid-template-columns: repeat(4, minmax(250px, 1fr));
            gap: 14px;
            padding: 0 16px 16px;
            align-items: start;
        }

        .list {
            background: var(--panel);
            border-radius: 12px;
            padding: 10px;
            min-height: 420px;
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: 700;
            color: #44546f;
        }

        .count {
            font-size: 0.85rem;
            background: #dfe1e6;
            border-radius: 8px;
            padding: 2px 8px;
        }

        .task-card {
            background: var(--card);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 1px 2px rgba(9, 30, 66, 0.18);
            cursor: grab;
        }

        .task-card:active {
            cursor: grabbing;
        }

        .task-card.dragging {
            opacity: 0.55;
        }

        .list.drag-over {
            outline: 2px dashed #0f766e;
            outline-offset: -4px;
        }

        .task-title {
            margin: 0 0 8px;
            font-size: 0.98rem;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 0.82rem;
        }

        .list-add {
            display: block;
            width: 100%;
            padding: 10px;
            border: 0;
            border-radius: 8px;
            background: #dfe1e6;
            color: #1d2125;
            text-align: left;
            font-weight: 600;
            cursor: pointer;
        }

        .empty {
            color: var(--muted);
            font-size: 0.9rem;
            padding: 12px;
            border: 1px dashed #c5cad4;
            border-radius: 8px;
            background: #fff;
        }

        @media (max-width: 980px) {
            .board {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <h1>SISTEM MANAJEMEN CADS</h1>
        <span class="chip">Total Task: <?= count($tasks) ?></span>
        <span class="chip">Mode: Trello Style</span>
    </header>

    <?php if ($message !== ''): ?>
        <p class="msg">Success: <?= e($message) ?></p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p class="err">Error: <?= e($error) ?></p>
    <?php endif; ?>

    <section class="navbar">
        <a class="active" href="index.php">Board</a>
        <a href="create_frontend.php">Create Task</a>
        <a href="update_frontend.php">Update Task</a>
        <a href="delete_frontend.php">Delete Task</a>
    </section>

    <div class="board">
        <section class="list" data-status="todo">
            <div class="list-header">
                <span>To Do</span>
                <span class="count"><?= count($groupedTasks['todo']) ?></span>
            </div>
            <?php if (count($groupedTasks['todo']) === 0): ?>
                <div class="empty">Belum ada task To Do.</div>
            <?php endif; ?>
            <?php foreach ($groupedTasks['todo'] as $task): ?>
                <article
                    class="task-card"
                    id="task-<?= (int) $task['id'] ?>"
                    draggable="true"
                    data-task-id="<?= (int) $task['id'] ?>"
                >
                    <h3 class="task-title"><?= e((string) $task['title']) ?></h3>
                    <div class="meta">
                        <span>Task #<?= (int) $task['id'] ?></span>
                        <span>Status: To Do</span>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="list" data-status="in-progress">
            <div class="list-header">
                <span>Doing</span>
                <span class="count"><?= count($groupedTasks['in-progress']) ?></span>
            </div>
            <?php if (count($groupedTasks['in-progress']) === 0): ?>
                <div class="empty">Belum ada task Doing.</div>
            <?php endif; ?>
            <?php foreach ($groupedTasks['in-progress'] as $task): ?>
                <article
                    class="task-card"
                    id="task-<?= (int) $task['id'] ?>"
                    draggable="true"
                    data-task-id="<?= (int) $task['id'] ?>"
                >
                    <h3 class="task-title"><?= e((string) $task['title']) ?></h3>
                    <div class="meta">
                        <span>Task #<?= (int) $task['id'] ?></span>
                        <span>Status: Doing</span>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="list" data-status="done">
            <div class="list-header">
                <span>Done</span>
                <span class="count"><?= count($groupedTasks['done']) ?></span>
            </div>
            <?php if (count($groupedTasks['done']) === 0): ?>
                <div class="empty">Belum ada task Done.</div>
            <?php endif; ?>
            <?php foreach ($groupedTasks['done'] as $task): ?>
                <article
                    class="task-card"
                    id="task-<?= (int) $task['id'] ?>"
                    draggable="true"
                    data-task-id="<?= (int) $task['id'] ?>"
                >
                    <h3 class="task-title"><?= e((string) $task['title']) ?></h3>
                    <div class="meta">
                        <span>Task #<?= (int) $task['id'] ?></span>
                        <span>Status: Done</span>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="list" id="add-list-panel">
            <div class="list-header">
                <span>Add another list</span>
                <span class="count">+</span>
            </div>
            <div class="task-card">
                <p class="task-title">List tambahan bisa dipakai untuk workflow lain.</p>
                <button class="list-add" id="add-list-btn" type="button">+ Tambah List Lainnya</button>
            </div>
        </section>
    </div>

    <script>
        (function () {
            var addButton = document.getElementById('add-list-btn');
            var addListPanel = document.getElementById('add-list-panel');
            var board = document.querySelector('.board');
            var statusLists = Array.prototype.slice.call(document.querySelectorAll('.list[data-status]'));

            function statusLabel(value) {
                if (value === 'todo') {
                    return 'To Do';
                }
                if (value === 'in-progress') {
                    return 'Doing';
                }
                return 'Done';
            }

            function countCards(listEl) {
                return listEl.querySelectorAll('.task-card[data-task-id]').length;
            }

            function updateListCount(listEl) {
                var countEl = listEl.querySelector('.count');
                if (!countEl) {
                    return;
                }

                countEl.textContent = String(countCards(listEl));
            }

            function ensureEmptyState(listEl) {
                var emptyEl = listEl.querySelector('.empty');
                var hasCards = countCards(listEl) > 0;

                if (hasCards && emptyEl) {
                    emptyEl.remove();
                    return;
                }

                if (!hasCards && !emptyEl) {
                    var status = listEl.getAttribute('data-status');
                    var placeholder = document.createElement('div');
                    placeholder.className = 'empty';
                    placeholder.textContent = 'Belum ada task ' + statusLabel(status) + '.';
                    listEl.appendChild(placeholder);
                }
            }

            function persistStatus(taskId, title, status) {
                var payload = new URLSearchParams();
                payload.set('id', String(taskId));
                payload.set('title', title);
                payload.set('status', status);

                fetch('update_backend.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: payload.toString()
                }).catch(function () {
                    window.alert('Gagal menyimpan perpindahan card. Coba refresh halaman.');
                });
            }

            statusLists.forEach(function (listEl) {
                listEl.addEventListener('dragover', function (event) {
                    event.preventDefault();
                    listEl.classList.add('drag-over');
                });

                listEl.addEventListener('dragleave', function () {
                    listEl.classList.remove('drag-over');
                });

                listEl.addEventListener('drop', function (event) {
                    event.preventDefault();
                    listEl.classList.remove('drag-over');

                    var draggedId = event.dataTransfer.getData('text/plain');
                    var card = document.getElementById(draggedId);
                    if (!card) {
                        return;
                    }

                    var sourceList = card.closest('.list[data-status]');
                    var targetStatus = listEl.getAttribute('data-status');
                    var titleEl = card.querySelector('.task-title');
                    var metaStatusEl = card.querySelector('.meta span:last-child');
                    var taskId = card.getAttribute('data-task-id');
                    var title = titleEl ? titleEl.textContent.trim() : '';

                    if (sourceList === listEl) {
                        return;
                    }

                    var targetEmpty = listEl.querySelector('.empty');
                    if (targetEmpty) {
                        targetEmpty.remove();
                    }

                    listEl.appendChild(card);

                    if (metaStatusEl) {
                        metaStatusEl.textContent = 'Status: ' + statusLabel(targetStatus);
                    }

                    if (sourceList) {
                        updateListCount(sourceList);
                        ensureEmptyState(sourceList);
                    }

                    updateListCount(listEl);
                    ensureEmptyState(listEl);

                    if (taskId && title !== '') {
                        persistStatus(taskId, title, targetStatus);
                    }
                });
            });

            var draggableCards = Array.prototype.slice.call(document.querySelectorAll('.task-card[data-task-id]'));
            draggableCards.forEach(function (card) {
                card.addEventListener('dragstart', function (event) {
                    card.classList.add('dragging');
                    event.dataTransfer.setData('text/plain', card.id);
                    event.dataTransfer.effectAllowed = 'move';
                });

                card.addEventListener('dragend', function () {
                    card.classList.remove('dragging');
                });
            });

            if (!addButton || !addListPanel || !board) {
                return;
            }

            addButton.addEventListener('click', function () {
                var listName = window.prompt('Masukkan nama list baru:');

                if (!listName) {
                    return;
                }

                listName = listName.trim();
                if (listName === '') {
                    window.alert('Nama list tidak boleh kosong.');
                    return;
                }

                var section = document.createElement('section');
                section.className = 'list';

                var header = document.createElement('div');
                header.className = 'list-header';

                var title = document.createElement('span');
                title.textContent = listName;

                var count = document.createElement('span');
                count.className = 'count';
                count.textContent = '0';

                header.appendChild(title);
                header.appendChild(count);

                var empty = document.createElement('div');
                empty.className = 'empty';
                empty.textContent = 'List baru berhasil ditambahkan.';

                section.appendChild(header);
                section.appendChild(empty);

                board.insertBefore(section, addListPanel);
            });
        })();
    </script>
</body>
</html>
