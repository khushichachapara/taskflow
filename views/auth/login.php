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
            margin: 5% auto;
        }

        .card {
            background: #fff;
            padding: 35px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

        p {
            text-align: center;
            margin-top: 15px;
        }

        p a {
            text-decoration: none;
            color: #459efe;
            font-weight: bold;
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

            <form method="POST" onsubmit="return validateLogin()">
                <input type="email" name="email" placeholder="Email Address">
                <input type="password" name="password" placeholder="Password">
                <small id=passError style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>
                <button type="submit">Login</button>
            </form>
            <p> Don't have account?
                <a href="/taskflow/register">Sign Up</a>
            </p>
        </div>
    </div>

    <script>
        let emailInput = document.querySelector('[name="email"]');

        let passwordInput = document.querySelector('[name="password"]');
        let passError = document.getElementById('passError');


        passwordInput.addEventListener('input', function() {
            let password = passwordInput.value.trim();

            if (password.length === 0) {
                passError.style.display = "none";
                passwordInput.style.border = "1px solid #ccc";
                return;
            }


            if (password.length < 6) {
                passError.style.display = "block";
                passError.innerText = "Password Must contain atleast 6 character ";
                passwordInput.style.border = "1px solid red";
                return;

            }
            passError.style.display = "none";
            passwordInput.style.border = "1px solid #ccc";
        });


        function validateLogin() {

            let email = emailInput.value.trim();
            let password = passwordInput.value.trim();

            if (email === "") {
                alert("Email is required");
                return false;
            }

            if (password === "") {
                alert("Password is required");
                return false;
            }

            return true;

        }
    </script>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>