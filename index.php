<?php
require_once 'includes/functions.php';
if (isLoggedIn()) {
    $role = $_SESSION['role'];
    $redirects = [
        'admin' => 'admin/dashboard.php',
        'pm' => 'pm/dashboard.php',
        'dev' => 'dev/dashboard.php',
        'tester' => 'tester/dashboard.php'
    ];
    header('Location: ' . ($redirects[$role] ?? 'login.php'));
} else {
    header('Location: login.php');
}
exit;
?>