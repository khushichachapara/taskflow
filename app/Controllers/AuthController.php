<?php

namespace TaskFlow\Controllers;

use TaskFlow\Repositories\UserRepository;

class AuthController
{



    private $userRepository;

    public function __construct($pdo)
    {
        $this->userRepository = new UserRepository($pdo);
    }



    //verify captcha and register user
    private function verifyRecaptcha(): bool
    {
        $secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        if (empty($recaptchaResponse)) {
            return false;
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'secret'   => $secretKey,
                'response' => $recaptchaResponse
            ]),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        // curl_close($ch);

        $responseData = json_decode($response);
        // var_dump($responseData);
        // exit;

        return isset($responseData->success) && $responseData->success === true;
    }


    //register controller method
    public function registerForm()
    {
        require __DIR__ . '/../../views/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');


            if ($name === '' || $email === '' || $password === '') {
                $_SESSION['register_error'] = "All fields are required.";
            } elseif (strlen($name) < 4) {
                $_SESSION['register_error'] = "Name must be at least 4 characters.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['register_error'] = "Invalid email format.";
            } elseif (strlen($password) < 6) {
                $_SESSION['register_error'] = "Password must be at least 6 characters.";
            } elseif (!$this->verifyRecaptcha()) {
                $_SESSION['register_error']= "Captcha verification failed.";
            } elseif ($this->userRepository->findByEmail($email)) {
                $_SESSION['register_error'] = "Email already registered.";
            }

            if (!empty($_SESSION['register_error'])) {
                header('Location: /taskflow/register');
                exit;
            }

            // hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $this->userRepository->create([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword
            ]);

            header('Location: /taskflow/login');
            exit;
        }
    }




    //login controller method

    public function loginForm()
    {
        require __DIR__ . '/../../views/auth/login.php';
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /taskflow');
            exit;
        }

    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //$username = $_POST['username'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
               $_SESSION['login_error'] = " All Feilds are required.";
            } elseif ($this->verifyRecaptcha() === false) {
                $_SESSION['login_error'] = "Captcha verification failed.";
            } else {
                $user = $this->userRepository->findByEmail($email);
                //print_r($user);

                if ($user &&  password_verify($password, $user->password)) {

                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user->id;
                    // $_SESSION['user'] = [
                    //     'id' => $user->id,
                    //     'name' => $user->name,
                    //     'email' => $user->email
                    // ];
                    $_SESSION['user_name'] = $user->name;
                    //echo 'hello'. $_SESSION['user_name'];
                    $_SESSION['login_success'] = true;
                    header('Location: /taskflow');
                    exit;
                } else {
                    $_SESSION['login_error']  = 'Invalid Email or password';
                }
            }
        }
        require __DIR__ . '/../../views/auth/login.php';
    }



    public function logout()
    {
        $basePath = '/taskflow';
        session_destroy();
        header('Location: ' . $basePath . '/');
        exit;
    }
}
