<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /home');
    exit;
}

$date       = $_POST['date']       ?? '';
$time       = $_POST['time']       ?? '';
$guests     = intval($_POST['guests']     ?? 2);
$seating    = trim(htmlspecialchars($_POST['seating']    ?? ''));
$menu_items = trim(htmlspecialchars($_POST['menu_items'] ?? ''));
$requests   = trim(htmlspecialchars($_POST['requests']   ?? ''));
$user_id    = $_COOKIE['user_id'] ?? null;

if (empty($date) || empty($time) || empty($seating)) {
    header('Location: /home?booking=error');
    exit;
}

$guests = max(1, min(20, $guests));

$db   = getDB();
$stmt = $db->prepare(
    "INSERT INTO reservations (user_id, date, time, guests, seating, menu_items, requests) VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param('issssss', $user_id, $date, $time, $guests, $seating, $menu_items, $requests);

if ($stmt->execute()) {
    $stmt->close();
    $db->close();
    header('Location: /home?booking=success');
    exit;
} else {
    $stmt->close();
    $db->close();
    header('Location: /home?booking=error');
    exit;
}
