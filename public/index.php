<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require __DIR__ . '/../vendor/autoload.php';


use TaskFlow\Controllers\HomeController;
use TaskFlow\Controllers\AuthController;
use TaskFlow\Controllers\TaskController;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
// $db = TaskFlow\Core\Database::connect();
// echo "DB connected successfully";
// die;


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
    '/api/tasks'
];

if (in_array($uri, $protectedRoutes) && !isset($_SESSION['user'])) {
    header('Location: ' . $basePath . '/login');
    exit;
}

switch ($uri) {

    case '/':
        (new HomeController())->index();
        break;

    case '/login':
        (new AuthController())->login();
        break;

    case '/logout':
        session_destroy();
        header('Location: ' . $basePath . '/');
        break;

    case '/tasks':
        (new TaskController())->index();
        break;
    case '/tasks/view':
        (new TaskController())->view($_GET['id']);
        break;


    case '/tasks/create':
        (new TaskController())->create();
        break;

    case '/tasks/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new TaskController())->store();
        }
        else {
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
        }
        else {
        header("Location: /taskflow/tasks");
        exit;
    }
        break;


    case '/api/tasks':
        (new TaskController())->apiList();
        break;

    case '/tasks/delete':
        (new TaskController())->softdelete($_GET['id']);
        break;

    default:
        echo "404 - Page Not Found";
}
