<?php

namespace TaskFlow\Middleware;

use TaskFlow\Core\Csrf;

class CsrfMiddleware
{
    public static function handle(): void
    {
        // Only protect POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $formKey = $_POST['_csrf_key'] ?? 'default';
        $token = $_POST['_csrf_token']?? null;

        if (!\TaskFlow\Core\Csrf::verify($formKey, $token) ) {

           $_SESSION['csrf_error'] = 'Security validation failed. Please try again.';

            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/taskflow'));
            exit;
        }
    }
}