<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['admin_login'])) {
    header('Location: admin_login.html');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: admin_login.html?error=empty');
    exit;
}

$db   = getDB();
$stmt = $db->prepare("SELECT id, name, email, password FROM admins WHERE email = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$admin  = $result->fetch_assoc();
$stmt->close();
$db->close();

if ($admin && ($password === $admin['password'] || password_verify($password, $admin['password']))) {
    $_SESSION['admin_id']   = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['is_admin']   = true;
    header('Location: admin_dashboard.php');
    exit;
} else {
    header('Location: admin_login.html?error=invalid');
    exit;
}
