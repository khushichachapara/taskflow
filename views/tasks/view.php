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
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-top: 0;
            color: #1f2937;
            font-size: xx-large;
        }

        .section {
            margin-top: 25px;
        }

        .comment-box textarea {
            width: 96%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
        }

        .cmtbtn {
            padding: 7px 14px;
            background: #459efe;
            color: #fff;
            font-size: medium;
            border: 1px solid #459efe;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .cmtbtn:hover {
            background: #fff;
            color: #459efe;
        }


        .comment {
            background: #f9fafb;
            border: 1px solid #adadaf;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;

        }

        .comment-text {
            font-size: 14px;
            color: #111827;

        }

        .log {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .log-time {
            font-size: 13px;
            color: #6b7280;
        }


        .actions .btn {
            display: inline-block;
            padding: 7px 14px;
            background: #459efe;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 10px;
            margin-bottom: 15px;
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

        .btn-back {

            float: right;
            padding: 7px 12px;
            background: #6b7280;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            border: 1px solid #6b7280;
        }

        .btn-back:hover {
            background: #fff;
            color: #6b7280;
        }


        /* alert box css */
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
    </style>
</head>

<body>

    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container">

        <div style="margin-bottom:15px;">
            <a href="<?= $basePath ?>/tasks" class="btn-back">← Back to Task List</a>
        </div>

        <!-- task info -->
        <h2><?= htmlspecialchars($task->title) ?></h2>
        <?php if ($isDeleted): ?>
            <div style="
        background:#fee2e2;
        color:#991b1b;
        padding:10px;
        border-radius:6px;
        margin-bottom:15px;
        font-weight:bold;
    ">
                ⚠ This task has been deleted. It is now read-only.
            </div>
        <?php endif; ?>

        <p><strong>Description:</strong> <?= htmlspecialchars($task->description) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($task->status) ?></p>
        <p><strong>Priority:</strong> <?= htmlspecialchars($task->priority) ?></p>


        <!-- button -->
        <div class="actions">
            <?php if (!$isDeleted): ?>
                <a href="<?= $basePath ?>/tasks/edit?id=<?= htmlspecialchars($task->id) ?>" class="btn">
                    Edit
                </a>

                <form method="POST"
                    action="<?= $basePath ?>/tasks/delete"
                    style="display:inline;"
                    id="deleteForm">

                    <input type="hidden" name="_csrf_key" value="tasks_delete">

                    <input type="hidden"
                        name="_csrf_token"
                        value="<?= \TaskFlow\Core\Csrf::generate('tasks_delete'); ?>">

                    <input type="hidden"
                        name="id"
                        value="<?= htmlspecialchars($task->id) ?>">

                    <input type="hidden"
                        name="redirect_to"
                        value="view">

                    <button type="button"
                        class="btn delete"
                        onclick="deleteConfirm()">
                        Delete
                    </button>
                </form>
            <?php else: ?>
                <span style="color:gray; font-weight:bold;">
                    Task Deleted
                </span>
            <?php endif; ?>
        </div>

        <hr />
        <!-- Comments Section -->
        <div class="section">
            <h3>Comments</h3>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-text">
                            <?= htmlspecialchars($comment->comment) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #d11a2a;">No comments yet.</p>
            <?php endif; ?>

            <?php if (!$isDeleted): ?>
                <div class="comment-box">
                    <form method="post" action="<?= $basePath ?>/comments/store">
                        <input type="hidden" name="_csrf_key" value="comments_store">
                        <input type="hidden" name="_csrf_token" value="<?= \TaskFlow\Core\Csrf::generate('comments_store'); ?>">
                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                        <textarea name="comment" placeholder="Write a comment..." required></textarea>
                        <button class="cmtbtn" type="submit">Add Comment</button>
                    </form>
                </div>
            <?php else: ?>
                <p style="color:#991b1b; font-weight:bold;">
                    Comments are disabled for deleted tasks.
                </p>
            <?php endif; ?>
        </div>

        <hr />

        <div class="section">
            <h3>Activity Log</h3>

            <?php foreach ($logs as $log): ?>
                <div class="log">
                    <?= htmlspecialchars($log['event_message']) ?>
                    <span class="log-time">
                        <?= date('d M Y, h:i A', strtotime($log['created_at'])) ?>
                    </span>

                </div>
            <?php endforeach; ?>

        </div>

    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script>
        function deleteConfirm() {

            Swal.fire({
                title: 'Are you sure?',
                text: "This task will be deleted.",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'my-confirm-btn',
                    cancelButton: 'my-cancel-btn'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();

                }
            });
        }
    </script>
</body>

</html>