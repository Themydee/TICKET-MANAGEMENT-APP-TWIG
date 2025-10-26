<?php


session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Set up Twig
$loader = new FilesystemLoader(__DIR__ . '/../src/templates');
$twig = new Environment($loader, [
    'cache' => false,
    'debug' => true,
]);

// Get current page from URL
$page = $_GET['page'] ?? 'landing';

// Check if user is authenticated for protected routes
$protected_routes = ['dashboard', 'tickets'];
if (in_array($page, $protected_routes)) {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }
}

// Prevent accessing auth pages when already logged in
$auth_routes = ['login', 'signup'];
if (in_array($page, $auth_routes) && isset($_SESSION['user'])) {
    header('Location: index.php?page=dashboard');
    exit;
}

// Handle logout
if ($page === 'logout') {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// Render pages based on route
switch ($page) {
    case 'landing':
        echo $twig->render('landing.twig');
        break;

    case 'login':
        echo $twig->render('auth.twig', ['isLogin' => true]);
        break;

    case 'signup':
        echo $twig->render('auth.twig', ['isLogin' => false]);
        break;

    case 'dashboard':
        echo $twig->render('dashboard.twig', [
            'user' => $_SESSION['user']
        ]);
        break;

    case 'tickets':
        $action = $_GET['action'] ?? 'list';
        echo $twig->render('tickets.twig', [
            'user' => $_SESSION['user'],
            'action' => $action,
            'currentPage' => 'tickets'
        ]);
        break;

    default:
        http_response_code(404);
        echo $twig->render('error.twig', ['message' => 'Page not found']);
        break;
}