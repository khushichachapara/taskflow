<!DOCTYPE html>
<style>
    .navbar {
        background: whitesmoke;
        padding: 30px;
         display: flex;
        justify-content: space-between;
        align-items: center;
        color: #459efe;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    .navbar .brand a {
        font-size: 30px;
        font-weight: bold;
        color: #459efe;
        text-decoration: none;
    }

    .navbar .links a {
        background: #459efe;
        color: #e0e7ff;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: large;
        text-decoration: none;
        font-weight: bold;
        border: solid #459efe 1px;
        margin-left: 10px;
        transition: all 0.2s ease;
    }

    .navbar .links a:hover {
        background: #e0e7ff;
        color: #459efe;
    }
</style>

<nav class="navbar">
    <div class="brand"><a href="/taskflow">TaskFlow</a></div>
    <?php
    $currentpath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $hideNavOn = [
        '/taskflow/login',
        '/taskflow/tasks/create',
        '/taskflow/tasks/edit',
        '/taskflow/tasks'
    ];
    if (!in_array($currentpath, $hideNavOn)):
    ?>
        <div class="links">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="tasks">Tasks</a>
                <a href="tasks/create">Create Task</a>
                <a href="logout">Logout</a>
            <?php else: ?>
                <a href="login">Login</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</nav>