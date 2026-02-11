<!DOCTYPE html>
<html>
<head>
    <title>Login | TaskFlow</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .login-box {    
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10% auto;
        }

        .card {
            background: #fff;
            padding: 35px;
            width: 320px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 30px;
            text-align: center;
            color: #1f2937;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 107.5%;
            padding: 10px;
            background: #459efe;
            border: solid #459efe 1px;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
           background-color: #fff;
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

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="login-box">
    <div class="card">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
