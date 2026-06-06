<?php
session_start();
setcookie('user_id', '', time() - 3600, '/');
setcookie('user_name', '', time() - 3600, '/');
header('Location: /home');
exit;
?>
