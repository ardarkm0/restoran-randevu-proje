<?php
// ── Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');         // MySQL kullanıcı adınızı yazın
define('DB_PASS', '');             // MySQL şifrenizi yazın
define('DB_NAME', 'lumina_db');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
