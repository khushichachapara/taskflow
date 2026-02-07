<!DOCTYPE html>
<html>

<head>
    <title>TaskFlow | Tasks</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
        }

        .container {
            width: 90%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-bottom: 15px;
            color: #1f2937;
            display: flex;
            justify-content: center;
        }

        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filters select,
        .filters input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filters button {
            background: #459efe;
            color: white;
            border: solid #459efe 1px;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .filters button:hover {
            background: white;
            color: #459efe;

        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background: #eef2ff;
        }

        .actions form {
            display: inline;
        }

        .actions button {
            background: #459efe;
            color: #fff;
            border: 1px solid #459efe;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .actions button:hover {
            background: #fff;
            color: #459efe;
        }

        .actions button.delete {
            background: #d11a2a;
            border-color: #d11a2a;
            margin-top: 5%;
        }

        .actions button.delete:hover {
            background: #fff;
            color: #d11a2a;
        }
    </style>
</head>

<body>

    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container">
        <h2>Task List</h2>

        <div class="filters">
            <select>
                <option>Status</option>
                <option>Pending</option>
                <option>In Progress</option>
                <option>Completed</option>
            </select>

            <select>
                <option>Sort</option>
                <option>Created At</option>
                <option>Priority</option>
            </select>

            <input type="text" placeholder="Search...">

            <button>Apply</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Created</th>
                    <th>Comments</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task->title) ?></td>
                        <td><?= htmlspecialchars($task->status) ?></td>
                        <td><?= htmlspecialchars($task->priority) ?></td>
                        <td><?= htmlspecialchars($task->created_at) ?></td>
                        <td><?= htmlspecialchars($task->comment_count) ?></td>

                        <td class="actions">
                            <form action="<?= $basePath ?>/tasks/view" method="get" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">
                                <button type="submit">View</button>
                            </form>

                            <form action="<?= $basePath ?>/tasks/edit" method="get" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">
                                <button type="submit">Edit</button>
                            </form>

                            <form action="<?= $basePath ?>/tasks/delete" method="get" style="display:inline;"
                                onsubmit="return confirm('Delete this task?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">
                                <button type="submit" class="delete">Delete</button>
                            </form>
                            </td>

                    </tr>
                <?php endforeach; ?>


            </tbody>
        </table>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>