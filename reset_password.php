<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.html');
    exit;
}

$email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$code     = trim($_POST['code'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($code) || empty($password)) {
    header('Location: reset_password.html?error=empty&email=' . urlencode($email));
    exit;
}

if (strlen($password) < 6) {
    header('Location: reset_password.html?error=short&email=' . urlencode($email));
    exit;
}

$db = getDB();

// Ensure table exists
$db->query("
    CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(150) NOT NULL,
        token VARCHAR(6) NOT NULL,
        expires_at DATETIME NOT NULL,
        used TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");

$stmt = $db->prepare(
    "SELECT id FROM password_resets WHERE email=? AND token=? AND expires_at > NOW() AND used=0"
);
$stmt->bind_param('ss', $email, $code);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close(); $db->close();
    header('Location: reset_password.html?error=invalid&email=' . urlencode($email));
    exit;
}
$stmt->close();


// Mark token used
$upd = $db->prepare("UPDATE password_resets SET used=1 WHERE email=? AND token=?");
$upd->bind_param('ss', $email, $code);
$upd->execute();
$upd->close();

// Update user password
$hashed = password_hash($password, PASSWORD_BCRYPT);
$pw = $db->prepare("UPDATE users SET password=? WHERE email=?");
$pw->bind_param('ss', $hashed, $email);
$pw->execute();
$pw->close();
$db->close();

header('Location: index.html?reset=success');
exit;
