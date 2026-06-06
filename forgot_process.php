<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.html');
    exit;
}

$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

if (empty($email)) {
    header('Location: forgot_password.html?error=empty');
    exit;
}

$db = getDB();

// Ensure password_resets table exists
$db->query("
    CREATE TABLE IF NOT EXISTS password_resets (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        email       VARCHAR(150) NOT NULL,
        token       VARCHAR(6)   NOT NULL,
        expires_at  DATETIME     NOT NULL,
        used        TINYINT(1)   NOT NULL DEFAULT 0,
        created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");

// Check user exists (don't reveal if not found)
$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
$exists = $stmt->num_rows > 0;
$stmt->close();

if (!$exists) {
    $db->close();
    header('Location: forgot_password.html?sent=1'); // prevent enumeration
    exit;
}

// Delete old tokens
$del = $db->prepare("DELETE FROM password_resets WHERE email = ?");
$del->bind_param('s', $email);
$del->execute();
$del->close();

// Generate 6-digit code
$code    = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

$ins = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
$ins->bind_param('sss', $email, $code, $expires);
$ins->execute();
$ins->close();
$db->close();

// Send email via PHP mail()
$subject = 'Lumina – Password Reset Code';
$message = "Your Lumina password reset code is:\n\n  $code\n\nThis code expires in 15 minutes.\n\nIf you did not request this, please ignore this email.";
$headers = "From: noreply@lumina-dining.com\r\nContent-Type: text/plain; charset=UTF-8";
mail($email, $subject, $message, $headers);

header('Location: reset_password.html?email=' . urlencode($email) . '&sent=1');
exit;
