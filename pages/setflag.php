<?php
//every time we use a get method, we use this php document to prevent submit a form twice or more 
session_start();
$_SESSION['flag'] = 1;
?>


