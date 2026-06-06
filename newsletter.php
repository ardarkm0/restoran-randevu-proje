<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: home.php');
    exit;
}

$email = trim(filter_input(INPUT_POST, 'nl_email', FILTER_SANITIZE_EMAIL));

if (!empty($email)) {
    $db = getDB();
    
    // Ensure subscribers table exists
    $db->query("
        CREATE TABLE IF NOT EXISTS subscribers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(150) NOT NULL UNIQUE,
            subscribed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    $stmt = $db->prepare("INSERT IGNORE INTO subscribers (email) VALUES (?)");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

// Redirect back to home with a success anchor or parameter
header('Location: home.php?subscribed=1#contact');
exit;
