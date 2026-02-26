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

        .g-recaptcha {
            margin: 15px 0;

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
    </style>



</head>

<body>

    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <div class="login-box">
        <div class="card">
            <h2>Login</h2>

            <?php if (!empty($_SESSION['login_error'])): ?>
                <div class="error">
                    <?= htmlspecialchars($_SESSION['login_error']) ?>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['csrf_error'])): ?>
                <div class="error">
                    <?= htmlspecialchars($_SESSION['csrf_error']) ?>
                </div>
                <?php unset($_SESSION['csrf_error']); ?>
            <?php endif; ?>

            <form method="POST" id='loginForm'>
                <input type="hidden" name="_csrf_key" value="login_form">
                <input type="hidden" name="_csrf_token" value="<?= \TaskFlow\Core\Csrf::generate('login_form'); ?>">

                <input type="email" name="email" placeholder="Email Address" required>
                <small id=emailError style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>
                <input type="password" name="password" placeholder="Password" required>
                <small id=passError style="color:red; display:none; font-size: medium; margin-bottom: 8px;"></small>
                <div class="g-recaptcha"
                    data-sitekey="<?= $_ENV['RECAPTCHA_SITE_KEY']; ?>"
                    data-callback="onCaptchaSuccess"
                    data-size="invisible">
                </div>
                <button type="button" onclick="validateForm()">Login</button>
                <!-- <button type="submit">Login</button> -->
            </form>
            <p> Don't have account?
                <a href="/taskflow/register">Sign Up</a>
            </p>
        </div>
    </div>

    <script>
        let emailInput = document.querySelector('[name="email"]');
        let emailError = document.getElementById('emailError');

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
        emailInput.addEventListener('input', function() {
            let email = emailInput.value.trim();
            if (email.length === 0) {
                emailError.style.display = "none";
                emailInput.style.border = "1px solid #ccc";
                return;
            }
            // Simple email format validation
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                emailError.style.display = "block";
                emailError.innerText = "Please enter a valid email address.";
                emailInput.style.border = "1px solid red";
            } else {
                emailError.style.display = "none";
                emailInput.style.border = "1px solid #ccc";
            }
        });

        function validateForm() {

            let email = emailInput.value.trim();
            let password = passwordInput.value.trim();

            if (email === "" || password === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'All fields are required!',
                    text: 'Please fill in both email and password.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'my-confirm-btn'
                    },
                    buttonsStyling: false
                });
                return;
            }

            // Only if frontend validation passes → run captcha
            grecaptcha.execute();
        }

        function onCaptchaSuccess(token) {
            document.getElementById('loginForm').submit();
        }
    </script>

    <?php require __DIR__ . '/../partials/footer.php'; ?>


</body>

</html>