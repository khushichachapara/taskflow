<?php
session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// protected routes
$protectedRoutes = [
    '/tasks',
    '/tasks/create',
    '/tasks/edit',
    '/tasks/view',
    '/api/tasks'
];

if (in_array($uri, $protectedRoutes) && !isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
