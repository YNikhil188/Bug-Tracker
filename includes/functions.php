<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }
}

function requireRole($roles) {
    requireLogin();
    if (!in_array($_SESSION['role'], (array)$roles)) {
        header('Location: ../login.php?error=access_denied');
        exit;
    }
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function uploadFile($file, $targetDir) {
    $targetFile = $targetDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return basename($file['name']);
    }
    return false;
}

function sendEmail($to, $subject, $message) {
    $headers = "From: bugtracker@example.com\r\n";
    $headers .= "Reply-To: bugtracker@example.com\r\n";
    $headers .= "Content-Type: text/html\r\n";
    return mail($to, $subject, $message, $headers);
}

function getUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>