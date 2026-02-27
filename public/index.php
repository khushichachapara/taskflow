<?php
ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/logs/app.log');
session_start();
require __DIR__ . '/../vendor/autoload.php';

use TaskFlow\Controllers\HomeController;
use TaskFlow\Controllers\AuthController;
use TaskFlow\Controllers\TaskController;
use TaskFlow\Controllers\CommentController;
use TaskFlow\Core\Csrf;
use TaskFlow\Middleware\CsrfMiddleware;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
// $db = TaskFlow\Core\Database::connect();
// echo "DB connected successfully";
// die;

// $redis = new \Redis();

// if ($redis->connect('127.0.0.1', 6379)) {
//     echo "Connected successfully";
// } else {
//     echo "Connection failed";
// }
$pdo = \TaskFlow\Core\Database::connect();

$basePath = '/taskflow';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace($basePath, '', $uri);
$uri = str_replace('/index.php', '', $uri);
$uri = $uri === '' ? '/' : $uri;



$protectedRoutes = [
    '/tasks',
    '/tasks/create',
    '/tasks/edit',
    '/tasks/view',
    '/tasks/store',
    '/tasks/delete',
    '/tasks/ajax-delete',
    '/comments/store',
    '/api/tasks'
];

if (in_array($uri, $protectedRoutes) && !isset($_SESSION['user_id'])) {
    header('Location: ' . $basePath . '/login');
    exit;
}


//csrf protection for POST routes
$csrfProtectedPostRoutes = [
    '/tasks/store',
    '/tasks/update',
    '/tasks/delete',
    '/comments/store',
    '/tasks/ajax-delete',
    '/login',
    '/register'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($uri, $csrfProtectedPostRoutes)) {
    CsrfMiddleware::handle();
}






switch ($uri) {

    case '/':
        (new HomeController())->index();
        break;


    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController($pdo))->login();
        } else {
            (new AuthController($pdo))->loginForm();
        }
        break;


    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController($pdo))->register();
        } else {
            (new AuthController($pdo))->registerForm();
        }
        break;

    case '/logout':
        (new AuthController($pdo))->logout();
        break;


    case '/tasks':
        (new TaskController())->index();
        break;


    case '/tasks/view':
        if (!isset($_GET['id'])) {
            header("Location: /taskflow/tasks");
            exit;
        }
        (new TaskController())->view($_GET['id']);
        break;


    case '/tasks/create':
        (new TaskController())->create();
        break;


    case '/tasks/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new TaskController())->store();
        } else {
            header("Location: /taskflow/tasks");
            exit;
        }
        break;



    case '/tasks/edit':
        (new TaskController())->edit($_GET['id']);
        break;


    case '/tasks/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new TaskController())->update($_POST['id']);
        } else {
            header("Location: /taskflow/tasks");
            exit;
        }
        break;




    case '/api/tasks':
        (new TaskController())->apiList();
        break;



    case '/tasks/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new TaskController())->softdelete($_POST['id']);
        } else {
            header("Location: /taskflow/tasks");
            exit;
        }
        break;


    case '/tasks/ajax-delete':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        (new TaskController())->ajaxDelete();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
    break;



    case '/comments/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new CommentController())->store();
        } else {
            header("Location: /taskflow/tasks");
            exit;
        }
        break;



    default:
        echo " opps! 404 - Page Not Found";
}
