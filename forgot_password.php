<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

if (empty($email)) {
    header('Location: forgot_password.html?error=empty');
    exit;
}

$db = getDB();

// Check user exists
$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
$exists = $stmt->num_rows > 0;
$stmt->close();

// Always show success to prevent email enumeration
if (!$exists) {
    $db->close();
    header('Location: forgot_password.html?sent=1');
    exit;
}

// Delete old tokens for this email
$db->prepare("DELETE FROM password_resets WHERE email = ?")->execute() || true;
$del = $db->prepare("DELETE FROM password_resets WHERE email = ?");
$del->bind_param('s', $email);
$del->execute();
$del->close();

// Generate 6-digit code
$code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

$ins = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
$ins->bind_param('sss', $email, $code, $expires);
$ins->execute();
$ins->close();
$db->close();

// Send email
$subject = 'Lumina – Your Password Reset Code';
$message = "Your Lumina password reset code is:\n\n  $code\n\nThis code expires in 15 minutes.\n\nIf you did not request this, ignore this email.";
$headers = "From: noreply@lumina.com\r\nContent-Type: text/plain; charset=UTF-8";

mail($email, $subject, $message, $headers);

header('Location: reset_password.html?email=' . urlencode($email));
exit;
