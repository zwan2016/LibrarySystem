<?php
require_once'config.php';
session_start();
if(!isset($_SESSION['email'])){
	if(isset($_COOKIE['email'])){
		$_SESSION['email']['name'] = $_COOKIE['email'];
		if(isset($_COOKIE['admin'])){
			$_SESSION['email']['admin'] = $_COOKIE['admin'];
		}
	}
	else{
		$login_url = "./login.php";
		header('Location:'.$login_url);
	}
}

if($_SESSION['email']['admin'] == 0){
	$login_url = "./login.php";
	header('Location:'.$login_url);
}
if(isset($_POST['submit'])){
	$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	mysql_select_db(DB_NAME, $con);
	$ISBN = mysql_real_escape_string(trim($_POST['ISBN']), $con);
	$Title = mysql_real_escape_string(trim($_POST['Title']), $con);
	$Author = mysql_real_escape_string(trim($_POST['Author']), $con);
	$Year = mysql_real_escape_string(trim($_POST['Year']), $con);
	$Publisher = mysql_real_escape_string(trim($_POST['Publisher']), $con);
	$Stock = mysql_real_escape_string(trim($_POST['Stock']), $con);
	
	if(!empty($ISBN) && !empty($Title) && !empty($Author) && !empty($Year) && !empty($Publisher) && !empty($Stock)){
		$query = "INSERT INTO Books(ISBN,Title,Author,Year,Publisher,Stock)
VALUES('$ISBN', '$Title', '$Author', '$Year', '$Publisher', '$Stock')";
		if(!mysql_query($query, $con)){
			die('Error: '.mysql_error());
		}
		else{
			$home_url = "./updatebooks.php";
			header('Location:'.$home_url);
		}	
	}
}
?>
