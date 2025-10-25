<?php
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

// Render pages based on route
switch ($page) {
    case 'landing':
    case '':
        echo $twig->render('landing.twig');
        break;

    case 'login':
        echo $twig->render('auth.twig', ['isLogin' => true]);
        break;

    case 'signup':
        echo $twig->render('auth.twig', ['isLogin' => false]);
        break;

    case 'dashboard':
        echo $twig->render('dashboard.twig');
        break;

    case 'tickets':
        echo $twig->render('tickets.twig');
        break;

    default:
        http_response_code(404);
        echo $twig->render('landing.twig');
        break;
}
