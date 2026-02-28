<!DOCTYPE html>
<html>
<!-- <pre>
    <?php
    // print_r($_SESSION["_csrf_token"] ?? NULL);   
    ?>
</pre> -->

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
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1f2937;
        }

        label {
            font-size: medium;
        }

        input,
        textarea {
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

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 12px;
            padding: 10px;
            background: #459efe;
            color: #fff;
            border: 1px solid #459efe;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.2s;
        }

        .back-btn:hover {
            background-color: #fff;
            color: #459efe;
        }

        /* css for alert box */

        .my-confirm-btn {
            background: #459efe;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
            border: none;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .my-confirm-btn:hover {
            background: white;
            color: #459efe;
            border: 1px solid #459efe;
            transform: scale(1.05);
        }

        .my-cancel-btn {
            background: #d33;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .my-cancel-btn:hover {
            background: white;
            color: #d33;
            border: 1px solid #d33;
            transform: scale(1.05);
        }

        .swal2-popup button {
            width: auto !important;
        }
    </style>
</head>

<body>
    <?php require __DIR__ . "/../partials/navbar.php"; ?>

    <div class="container">
        <h2>Edit Task</h2>

        <form method="POST" action="<?= $basePath ?>/tasks/update" onsubmit="return confirmEdit(event)">
            <input type="hidden" name="_csrf_key" value="tasks_update">
            <input type="hidden" name="_csrf_token" value="<?= \TaskFlow\Core\Csrf::generate("tasks_update"); ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">

            <label>Title <span style="color: #ce0d0d;">*</span></label>
            <input type="text" name="title" value="<?= htmlspecialchars($task->title) ?> " required>

            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($task->description) ?></textarea>

            <label>Status</label>
            <select name="status">
                <option value="pending" <?= $task->status == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="in_progress" <?= $task->status == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= $task->status == 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>

            <label>Priority</label>
            <select name="priority">
                <option value="low" <?= $task->priority == 'low' ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= $task->priority == 'medium' ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= $task->priority == 'high' ? 'selected' : '' ?>>High</option>
            </select>

            <button type="submit">Update Task</button>
            <a href="<?= $basePath ?>/tasks" class="back-btn">Back to Tasks</a>
        </form>
    </div>

    <script>
        function confirmEdit(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to update this task!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes,update it!',
                cancelButtonText: 'Cancel',
                customClass: {

                    confirmButton: 'my-confirm-btn',
                    cancelButton: 'my-cancel-btn'
                },

            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });

            return false;
        }
    </script>
</body>

</html>