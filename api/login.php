<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
    header('Location: index.html');
    exit;
}

$email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: index.html?error=empty');
    exit;
}

$db   = getDB();
$stmt = $db->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();
$db->close();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header('Location: home.php');
    exit;
} else {
    header('Location: index.html?error=invalid');
    exit;
}
