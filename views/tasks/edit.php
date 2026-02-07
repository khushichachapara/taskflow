<!DOCTYPE html>
<html>
<head>
    <title>Task | Edit</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fa;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1f2937;
        }

        label {
            font-size: medium;
        }

        input, textarea{
            width: 95%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #459efe;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            border: #459efe solid 1px;
        }

        button:hover {
            background: #fff;
            color: #459efe;
        }
    </style>
</head>

<body>
<?php require __DIR__ ."/../partials/navbar.php"; ?>

<div class="container">
    <h2>Edit Task</h2>

    <form method="POST" action="<?= $basePath ?>/tasks/update">

        <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">

        <label>Title <span style="color: #ce0d0d;">*</span></label>
        <input type="text" name="title" value="<?= htmlspecialchars($task->title) ?> " required>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($task->description) ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $task->status=='pending'?'selected':'' ?>>Pending</option>
            <option value="in_progress" <?= $task->status=='in_progress'?'selected':'' ?>>In Progress</option>
            <option value="completed" <?= $task->status=='completed'?'selected':'' ?>>Completed</option>
        </select>

        <label>Priority</label>
        <select name="priority">
            <option value="low" <?= $task->priority=='low'?'selected':'' ?>>Low</option>
            <option value="medium" <?= $task->priority=='medium'?'selected':'' ?>>Medium</option>
            <option value="high" <?= $task->priority=='high'?'selected':'' ?>>High</option>
        </select>

        <button type="submit">Update Task</button>
    </form>
</div>

</body>
</html>
