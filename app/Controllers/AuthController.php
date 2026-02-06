<?php

namespace TaskFlow\Controllers;

class AuthController
{

    public function login()
    {

        if (isset($_SESSION['user'])) {
            header('Location: /taskflow');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['user'] = [
                    'username' => 'admin'
                ];

                header('Location: /taskflow');
                exit;
            } else {
                $error = 'Invalid username or password';
            }   
        }
        require __DIR__ . '/../../views/auth/login.php';
    }

    // public function logout() {
    //     session_destroy();
    //     header('Location: /taskflow');
    //     exit;
    // }
}
