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
        $token = $_POST['_csrf_token'] ?? null;

        if (!Csrf::verify($formKey, $token)) {


            // 🔍 Detect AJAX
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'CSRF validation failed'
                ]);
                exit;
            }

            $_SESSION['csrf_error'] = 'Security validation failed. Please try again.';

            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/taskflow'));
            exit;
        }
    }
}
