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
            margin: 4% auto;
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

        .g-recaptcha {
            margin: 15px 0;

        }
        /* css for alert */
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
    </style>
</head>

<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>
    <div class="form-wrapper">
        <div class="form-box">
            <h2>Create Account</h2>


            <?php if (!empty($_SESSION['register_error'])): ?>
                <div class="error-box">
                    <?= htmlspecialchars($_SESSION['register_error']) ?>
                </div>
                <?php unset($_SESSION['register_error']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['csrf_error'])): ?>
                <div class="error-box">
                    <?= htmlspecialchars($_SESSION['csrf_error']) ?>
                </div>
                <?php unset($_SESSION['csrf_error']); ?>
            <?php endif; ?>

            <form method="POST" id='registerForm' action="/taskflow/register">
                <input type="hidden" name="_csrf_key" value="register_form">
                <input type="hidden" name="_csrf_token" value="<?= \TaskFlow\Core\Csrf::generate('register_form'); ?>">
                <input type="text" id="name" name="name" placeholder="Full Name">
                <small id="nameError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

                <input type="email" id="email" name="email" placeholder="Email Address">
                <small id="emailError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

                <input type="password" id="password" name="password" placeholder="Password">
                <small id="passwordError" style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>

                <div class="g-recaptcha"
                    data-sitekey="<?= $_ENV['RECAPTCHA_SITE_KEY']; ?>"
                    data-callback="onCaptchaSuccess"
                    data-size="invisible">
                </div>
                <button type="button" onclick="validateForm()">Sign Up</button>
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
                passwordInput.style.border = 'solid 1px #ccc';
                return;
            }


            if (password.length < 6) {
                passwordError.style.display = "block";
                passwordError.style.color = "red";
                passwordError.innerText = "Password must be at least 6 characters";
                passwordInput.style.border = '1px solid red';

            } else {
                passwordError.style.display = "block";
                passwordError.style.color = "green";
                passwordError.innerText = " ✅ Password looks good ";
                passwordInput.style.border = 'solid 1px #ccc';
            }
        });

        nameInput.addEventListener('input', function() {
            let name = nameInput.value.trim();
            let namepattern = /^[a-zA-Z \s]+$/;
            if (name === "") {
                nameError.style.display = "none";
                nameInput.style.border = '1px solid #ccc';
                return;
            }

            if (name.length < 4 || !namepattern.test(name)) {
                nameError.style.display = 'block';
                nameError.style.color = 'red';
                nameError.innerText = 'Name Should be of atleast 4 charachters and meaningful.';
                nameInput.style.border = '1px solid red';
            } else {
                nameError.style.display = 'block';
                nameError.style.color = 'green';
                nameError.innerText = '✅ Name looks Good'
                nameInput.style.border = '1px solid #ccc';
            }

        });

        emailInput.addEventListener("input", function() {
            let email = emailInput.value.trim();

            if (email === "") {
                emailError.style.display = "none";
                emailInput.style.border = '1px solid #ccc';
                return;
            }

            // Simple email regex
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                emailError.style.display = "block";
                emailError.style.color = "red";
                emailError.innerText = "Enter a valid email address";
                emailInput.style.border = '1px solid red';
            } else {
                emailError.style.display = "block";
                emailError.style.color = "green";
                emailError.innerText = " ✅ Email looks good ";
                emailInput.style.border = '1px solid #ccc';
            }
        });

        function validateForm() {
            let name = nameInput.value.trim();
            let email = emailInput.value.trim();
            let password = passwordInput.value.trim();

            if (name === "" || email === "" || password === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'All fields are required!',
                    text: 'Please fill in all the fields.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'my-confirm-btn'
                    },
                    buttonsStyling: false
                });
                return;
            }

            //  Only if frontend validation passes → run captcha
            grecaptcha.execute();
        }

        function onCaptchaSuccess(token) {
            document.getElementById('registerForm').submit();
        }
    </script>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>