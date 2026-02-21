<!DOCTYPE html>
<html>

<head>
    <title>Sign Up | TaskFlow</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
        }

        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        .form-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        .form-box h2 {
            margin-bottom: 20px;
            font-size: 30px;
            text-align: center;
            color: #1f2937;
        }

        .form-box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .form-box button {
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

        .form-box button:hover {
            background: #fff;
            color: #459efe;
        }

        .form-box p {
            text-align: center;
            margin-top: 15px;
        }

        .form-box a {
            color: #459efe;
            text-decoration: none;
            font-weight: bold;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            width: 100%;
        }

        .error-box ul {
            list-style: none;
            padding-left: 0;
            margin: 0;

        }

        .error-box li {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>
    <div class="form-wrapper">
        <div class="form-box">
            <h2>Create Account</h2>


            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="error-box">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form method="POST" action="/taskflow/register">
                <input type="text" id="name" name="name" placeholder="Full Name">
                <small id="nameError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

                <input type="email" id="email" name="email" placeholder="Email Address">
                <small id="emailError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

                <input type="password" id="password" name="password" placeholder="Password">
                <small id="passwordError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

                <button type="submit">Sign Up</button>
            </form>

            <p>Already have an account?
                <a href="/taskflow/login">Login</a>
            </p>
        </div>
    </div>
    <script>
        const passwordInput = document.getElementById("password");
        const passwordError = document.getElementById("passwordError");

        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');

        const emailInput = document.getElementById("email");
        const emailError = document.getElementById("emailError");


        passwordInput.addEventListener("input", function() {
            let password = passwordInput.value.trim();

            if (password === "") {
                passwordError.style.display = "none";
                return;
            }


            if (password.length < 6) {
                passwordError.style.display = "block";
                passwordError.style.color = "red";
                passwordError.innerText = "Password must be at least 6 characters";
            } else {
                passwordError.style.display = "block";
                passwordError.style.color = "green";
                passwordError.innerText = "Password looks good ✅";
            }
        });

        nameInput.addEventListener('input', function() {
            let name = nameInput.value.trim();

            if (name === "") {
                nameError.style.display = "none";
                return;
            }

            if (name.length < 4) {
                nameError.style.display = 'block';
                nameError.style.color = 'red';
                nameError.innerText = 'Name Should be of atleast 4 letters'
            } else {
                nameError.style.display = 'block';
                nameError.style.color = 'green';
                nameError.innerText = 'Name looks Good✅'
            }

        });

        emailInput.addEventListener("input", function() {
            let email = emailInput.value.trim();

            if (email === "") {
                emailError.style.display = "none";
                return;
            }

            // Simple email regex
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                emailError.style.display = "block";
                emailError.style.color = "red";
                emailError.innerText = "Enter a valid email address";
            } else {
                emailError.style.display = "block";
                emailError.style.color = "green";
                emailError.innerText = "Email looks good ✅";
            }
        });
    </script>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>