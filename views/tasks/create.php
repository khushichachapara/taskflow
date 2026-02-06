<!DOCTYPE html>
<html>
<head>
    <title>Task | Create    </title>

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
            font-weight: bold;
        }

        input, textarea{
            width: 95%;
            padding : 8px;
            margin-top:6px;
            margin-bottom: 15px ;
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
            background: #459efe ;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            border: #459efe solid 1px   ;
        }

        button:hover {
            background:#fff;
            color: #459efe;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
<?php require __DIR__ ."/../partials/navbar.php";?>
<div class="container">
    <h2>Create Task</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/taskflow/tasks/create">

        <label>Title *</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>

        <label>Priority</label>
        <select name="priority">
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
        </select>

        <button type="submit">Create Task</button>
    </form>
</div>

</body>
</html>
