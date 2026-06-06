<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register'])) {
    header('Location: /');
    exit;
}

$name     = trim(htmlspecialchars($_POST['name'] ?? ''));
$email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = $_POST['password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    header('Location: /?error=empty');
    exit;
}

if (strlen($password) < 6) {
    header('Location: /?error=short_password');
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);
$db     = getDB();

$check = $db->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param('s', $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    $db->close();
    header('Location: /?error=exists');
    exit;
}
$check->close();

$stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $name, $email, $hashed);

if ($stmt->execute()) {
    $_SESSION['user_id']   = $stmt->insert_id;
    $_SESSION['user_name'] = $name;
    $stmt->close();
    $db->close();
    header('Location: /home');
    exit;
} else {
    $stmt->close();
    $db->close();
    header('Location: /?error=db');
    exit;
}
