<?php
session_start();
setcookie('admin_id', '', time() - 3600, '/');
setcookie('admin_name', '', time() - 3600, '/');
setcookie('is_admin', '', time() - 3600, '/');
header('Location: /admin_login.html');
exit;
