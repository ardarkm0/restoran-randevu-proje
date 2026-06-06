<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['admin_login'])) {
    header('Location: /admin_login.html');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: /admin_login.html?error=empty');
    exit;
}

$db   = getDB();
$stmt = $db->prepare("SELECT id, name, email, password FROM admins WHERE email = ? OR name = ?");
$stmt->bind_param('ss', $username, $username);
$stmt->execute();
$result = $stmt->get_result();
$admin  = $result->fetch_assoc();
$stmt->close();
$db->close();

if ($admin && ($password === $admin['password'] || password_verify($password, $admin['password']))) {
    $expire = time() + 60 * 60 * 24 * 7; // 7 gün
    setcookie('admin_id',   $admin['id'],   $expire, '/', '', false, true);
    setcookie('admin_name', $admin['name'], $expire, '/', '', false, false);
    setcookie('is_admin',   '1',            $expire, '/', '', false, true);
    header('Location: /admin/dashboard');
    exit;
} else {
    header('Location: /admin_login.html?error=invalid');
    exit;
}
