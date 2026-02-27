<!DOCTYPE html>
<html>

<!-- <pre>
    <?php
    // print_r($_SESSION["_csrf_token"] ?? NULL);
    ?>
</pre> -->

<head>
    <title>TaskFlow | Tasks</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            /* background: linear-gradient(135deg, #459efe, #2563eb); */

        }

        .container {
            width: 90%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);

        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .create-btn {
            background: #459efe;
            color: #fff;
            border: 1px solid #459efe;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
        }

        .create-btn:hover {
            background: #fff;
            color: #459efe;
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

        .b a {
            background: #459efe;
            color: #fff;
            border: 1px solid #459efe;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin: 15px;

            float: right;
            text-decoration: none;


        }

        .btnall {
            background: #6b7280;
            color: #fff;
            border: 1px solid #6b7280;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 14px;
        }

        .btnall:hover {
            background: #fff;
            color: #6b7280;
        }

        .filters a.btnall {
            margin-left: 5px;
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
    </style>
</head>

<body>

    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container">
        <div class="header-row">
            <h2 style="color: #1f2937;">Task List</h2>
            <a class="create-btn" href="<?= $basePath ?>/tasks/create">Create Task</a>
        </div>

        <form method="get" action="<?= $basePath ?>/tasks">
            <div class="filters">

                <select name="status">
                    <option value="">Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>

                <select name="sort">
                    <option value="">Sort</option>
                    <option value="created_at">Created At</option>
                    <option value="priority">Priority</option>
                </select>

                <input type="text" name="search" placeholder="Search...">

                <button type="submit">Apply</button>



                <a href="<?= $basePath ?>/tasks"
                    class="btnall"
                    id="clearbtn"
                    onclick="return clearFilter(event);">
                    Clear Filter
                </a>
            </div>
        </form>

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

                <?php if (count($tasks) > 0): ?>

                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task->title) ?></td>
                            <td><?= htmlspecialchars($task->status) ?></td>
                            <td><?= htmlspecialchars($task->priority) ?></td>
                            <td><?= htmlspecialchars($task->created_at) ?></td>
                            <td><?= htmlspecialchars($task->comment_count ?? 0) ?></td>

                            <td class="actions">
                                <form action="<?= $basePath ?>/tasks/view" method="get" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">
                                    <button type="submit">View</button>
                                </form>

                                <form action="<?= $basePath ?>/tasks/edit" method="get" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">
                                    <button type="submit">Edit</button>
                                </form>

                                <form action="<?= $basePath ?>/tasks/delete" method="POST" style="display:inline;">
                                    <?php $csrfKey = 'tasks_delete_' . $task->id; ?>

                                    <input type="hidden" name="_csrf_key" value="<?= $csrfKey ?>">
                                    <input type="hidden" name="_csrf_token"
                                        value="<?= \TaskFlow\Core\Csrf::generate($csrfKey); ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">
                                    <button type="button" class="delete" onclick="return DeleteTask(event, this);">Delete</button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>

                    <tr>
                        <td colspan="6" style="text-align:center; padding:20px; color:red;">
                            No tasks found
                        </td>
                    </tr>

                <?php endif; ?>



            </tbody>
        </table>

        <!-- Pagination Partial -->
        <?php require __DIR__ . '/../partials/pagination.php'; ?>
        <script>
            function clearFilter(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "All Filters will be cleared .",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Clear',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'my-confirm-btn',
                        cancelButton: 'my-cancel-btn'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = event.target.href;
                    }
                });
            }
        </script>

        <script>
            function DeleteTask(event, btn) {

                const form = btn.closest('form');

                const taskId = form.querySelector('[name="id"]').value;
                const csrfKey = form.querySelector('[name="_csrf_key"]').value;
                const csrfToken = form.querySelector('[name="_csrf_token"]').value;

                const formData = new FormData();
                formData.append('id', taskId);
                formData.append('_csrf_key', csrfKey);
                formData.append('_csrf_token', csrfToken);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Task will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'my-confirm-btn',
                        cancelButton: 'my-cancel-btn'
                    },

                }).then((result) => {

                    if (result.isConfirmed) {

                        fetch('/taskflow/tasks/ajax-delete', {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(
                                data => {

                                    if (data.success) {

                                        
                                        // Swal.fire({
                                        //     title: 'Deleted!',
                                        //     text: data.message,
                                        //     icon: 'success',
                                        //     confirmButtonText: 'OK',
                                        //     customClass: {
                                        //         confirmButton: 'my-confirm-btn'
                                        //     },
                                        //     buttonsStyling: false
                                        // });
                                        // const row = form.closest('tr');
                                        // row.remove();

                                        // const remainingRows = document.querySelectorAll("tbody tr");

                                        // if (remainingRows.length === 1) {
                                        //     handleEmptyPage();
                                        // }

                                        const row = form.closest('tr');
                                        row.remove();

                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 400);

                                    } else {

                                        Swal.fire({
                                            title: 'Error',
                                            text: data.message,
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'my-confirm-btn'
                                            },
                                            buttonsStyling: false
                                        });

                                    }

                                });
                    }
                });

                return false;
            }
        </script>
        <script>
            function handleEmptyPage() {

                const url = new URL(window.location.href);
                let page = parseInt(url.searchParams.get('page')) || 1;

                if (page > 1) {
                    url.searchParams.set('page', page - 1);
                }

                window.location.href = url.toString();
            }
        </script>
    </div>


    <?php require __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>