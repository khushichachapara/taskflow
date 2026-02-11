<!DOCTYPE html>
<html>

<head>
    <title>Task | Details</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fa;
        }

        .container {
            width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            color: #1f2937;
        }

        .section {
            margin-top: 25px;
        }

        .comment-box textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        button {
            padding: 8px 16px;
            background: #459efe;
            color: #fff;
            border: 1px solid #459efe;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #fff;
            color: #459efe;
        }

        .log,
        .comment {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .actions .btn {
            display: inline-block;
            padding: 8px 14px;
            background: #459efe;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 10px;
            border: 1px solid #459efe;
        }

        .actions .btn:hover {
            background: #fff;
            color: #459efe;
        }

        /* delete button style */
        .actions .btn.delete {
            background: #d11a2a;
            border-color: #d11a2a;
        }

        /* delete hover */
        .actions .btn.delete:hover {
            background: #fff;
            color: #d11a2a;
        }
    </style>
</head>

<body>

    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container">
        <!-- task info -->
        <h2><?= htmlspecialchars($task->title) ?></h2>

        <p><strong>Description:</strong> <?= htmlspecialchars($task->description) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($task->status) ?></p>
        <p><strong>Priority:</strong> <?= htmlspecialchars($task->priority) ?></p>


        <!-- button -->
        <div class="actions">
            <a href="<?= $basePath ?>/tasks/edit?id=<?= htmlspecialchars($task->id) ?>" class="btn">
                Edit
            </a>

            <a href="<?= $basePath ?>/tasks/delete?id=<?= htmlspecialchars($task->id) ?>"
                class="btn delete"
                onclick="return confirm('Delete this task?')">
                Delete
            </a>
        </div>


        <!-- Comments Section -->
        <div class="section">
            <h3>Comments</h3>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <?= htmlspecialchars($comment->comment) ?>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>

            <div class="comment-box">
                <form method="post" action="<?= $basePath ?>/comments/store">
                    <input type="hidden" name="task_id" value="<?= $task->id ?>">
                    <textarea name="comment" placeholder="Write a comment..." required></textarea>
                    <button type="submit">Add Comment</button>
                </form>
            </div>
        </div>


        <div class="section">
            <h3>Activity Log</h3>

            <?php foreach ($logs as $log): ?>
                <div class="log">
                    <?= htmlspecialchars($log['event_message']) ?>
                    <small>(<?= $log['created_at'] ?>)</small>
                </div>
            <?php endforeach; ?>

        </div>

    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>