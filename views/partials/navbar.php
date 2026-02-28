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
        color: white;
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
        background: white;
        color: #459efe;
    }


    .user-box {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-right: 20px;
    }

    .user-circle {
        width: 40px;
        height: 40px;
        border: solid #fe5145 1px;
        border-radius: 50%;
        background: #459efe;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .user-name {
        color: #fe5145;
    }
</style>


<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php

//button visibility logic
$currentpath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');


$isLoginPage = ($currentpath === '/taskflow/login');
$isRegisterPage = ($currentpath === '/taskflow/register');
$isHome = ($currentpath === '/taskflow' || $currentpath === '');

$hideNavOn = [
    '/taskflow/login',
    '/taskflow/tasks/create',
    '/taskflow/tasks/edit',
    '/taskflow/tasks',
    '/taskflow/tasks/view'
];

//user info for navbar display
$userName = htmlspecialchars($_SESSION['user_name'] ?? '');
$initial = strtoupper(substr($userName, 0, 1));

?>



<!-- ui -->
<nav class="navbar">
    <div class="brand"><a href="/taskflow">TaskFlow</a></div>
    <div class="links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (in_array($currentpath, $hideNavOn)): ?>

                <!-- Circle shown -->
                <div class="user-box">
                    <div class="user-circle"><?= $initial ?></div>
                    <div class="user-name"><?= $userName ?></div>
                </div>
            <?php else: ?>
                <span style="color: #fe5145;">

                    <?php if ($isHome): ?>
                        Welcome, <?= $userName ?>
                    <?php else: ?>
                        <?= $userName ?>
                    <?php endif; ?>
                </span>


                <a href="tasks">Tasks</a>
                <a href="tasks/create">Create Task</a>
                <a href="logout" onclick="confirmLogout(event)">Logout</a>
            <?php endif; ?>
        <?php else: ?>

            <?php if ($isLoginPage): ?>
                <a href="register">Sign Up</a>

            <?php elseif ($isRegisterPage): ?>
                <a href="login">Login</a>

            <?php else: ?>
                <a href="login">Login</a>
                <a href="register">Sign Up</a>
            <?php endif; ?>

        <?php endif; ?>


    </div>

</nav>