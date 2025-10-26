<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function handleLogin($data) {
    if (!validateEmail($data['email']) || empty($data['password'])) {
        return ['success' => false, 'error' => 'Invalid credentials'];
    }

    // For demo purposes - in production, you'd verify against a database
    $_SESSION['user'] = [
        'email' => $data['email'],
        'username' => explode('@', $data['email'])[0]
    ];
    
    return ['success' => true];
}

function handleSignup($data) {
    if (!validateEmail($data['email'])) {
        return ['success' => false, 'error' => 'Invalid email'];
    }
    
    if (empty($data['username'])) {
        return ['success' => false, 'error' => 'Username is required'];
    }
    
    if (strlen($data['password']) < 6) {
        return ['success' => false, 'error' => 'Password must be at least 6 characters'];
    }

    // For demo purposes - in production, you'd save to a database
    $_SESSION['user'] = [
        'email' => $data['email'],
        'username' => $data['username']
    ];
    
    return ['success' => true];
}

try {
    switch ($action) {
        case 'login':
            echo json_encode(handleLogin($data));
            break;
        case 'signup':
            echo json_encode(handleSignup($data));
            break;
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Server error']);
}