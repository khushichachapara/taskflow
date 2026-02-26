<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Task | Create </title>

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
            font-family: Arial, sans-serif;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        input::placeholder,
        textarea::placeholder {
            font-size: 12px;
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
    <?php require __DIR__ . "/../partials/navbar.php"; ?>
    <div class="container">
        <h2>Create Task</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error">
                <?= htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= $basePath ?>/tasks/store" onsubmit="return validateTask()">
            <input type="hidden" name="_csrf_key" value="tasks_store">
            <input type="hidden" name="_csrf_token" value="<?= \TaskFlow\Core\Csrf::generate('tasks_store'); ?>">

            <label>Title <span style="color: #ce0d0d;">*</span></label>
            <input type="text" id='title' placeholder="Add Task Title here ..." name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
            <small id="textError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

            <label>Description</label>
            <textarea name="description" id='description' placeholder="Add Some Description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            <small id="descriptionError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

            <label>Status</label>
            <select name="status">
                <option value="pending" <?= (($_POST['status'] ?? '') == 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="in_progress" <?= (($_POST['status'] ?? '') == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= (($_POST['status'] ?? '') == 'completed') ? 'selected' : "" ?>>Completed</option>
            </select>

            <label>Priority</label>
            <select name="priority">
                <option value="low" <?= (($_POST['priority'] ?? '') == "low") ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= (($_POST['priority'] ?? '') == "medium") ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= (($_POST['priority'] ?? '') == "high") ? 'selected' : '' ?>>High</option>
            </select>

            <button type="submit">Create Task</button>
        </form>
    </div>
    <script>
        let titleInput = document.getElementById("title");
        let errorBox = document.getElementById("textError");

        let descriptionInput = document.getElementById("description");
        let descriptionError = document.getElementById('descriptionError');


        titleInput.addEventListener('input', function() {
            errorBox.style.display = 'none';
            titleInput.style.border = 'solid #ccc 1px';

        });


        descriptionInput.addEventListener('input', function() {
            descriptionError.style.display = 'none';
            descriptionInput.style.border = 'solid #ccc 1px';
        });

        function validateTask() {

            let isValid = true;

            let pattern = /^[a-zA-Z0-9 ]{5,}$/;
            let title = titleInput.value.trim();

            if (!pattern.test(title)) {
                errorBox.style.display = "block";
                errorBox.innerText = "Title must be at least 5 characters and contain only letters or numbers.";
                titleInput.style.border = "1px solid red";
                isValid = false;
            }

            let patternDesc = /^(?=.*[a-zA-Z])[a-zA-Z0-9 .!_,\-]{10,}$/;
            let description = descriptionInput.value.trim();

            if (description !== "") {

                if (!patternDesc.test(description)) {
                    descriptionError.style.display = "block";
                    descriptionError.innerText = "Please add a more meaningful description (at least 10 characters).";
                    descriptionInput.style.border = "1px solid red";
                    isValid = false;
                }

            }

            return isValid;
        }
    </script>
</body>

</html>