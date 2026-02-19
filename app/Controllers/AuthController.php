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




    //register controller method
    public function registerForm()
    {
        require __DIR__ . '/../../views/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];


            $errors = [];



            // Email validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            // Password validation
            if (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters";
            }

            // Check  email already exists
            if ($this->userRepository->findByEmail($email)) {
                $errors[] = "Email already registered";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
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

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //$username = $_POST['username'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userRepository->findByEmail($email);
            //print_r($user);

            if (
                $user &&  password_verify($password, $user->password)
            ) {
                $_SESSION['user_id'] = $user->id;
                // $_SESSION['user'] = [
                //     'id' => $user->id,
                //     'name' => $user->name,
                //     'email' => $user->email
                // ];
                $_SESSION['user_name'] = $user->name;
                //echo 'hello'. $_SESSION['user_name'];

                header('Location: /taskflow');
                exit;
            } else {
                $error = 'Invalid Email or password';
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
