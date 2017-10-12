<?php
session_start();
unset($_SESSION['email']);
setcookie('email', '', 1);
setcookie('admin', '', 1);
$login_url = "./login.php";
header('Location:'.$login_url);
?>
