<?php
require_once'config.php';
session_start();
if(isset($_POST['submit'])){
	$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	mysql_select_db(DB_NAME, $con);
	$email = mysql_real_escape_string(trim($_POST['email']), $con);
	$password = md5(mysql_real_escape_string(trim($_POST['password']), $con));
	$firstname = mysql_real_escape_string(trim($_POST['firstname']), $con);
	$lastname = mysql_real_escape_string(trim($_POST['lastname']), $con);
	$age = mysql_real_escape_string(trim($_POST['age']), $con);
	$phone = mysql_real_escape_string(trim($_POST['phone']), $con);
	$address = mysql_real_escape_string(trim($_POST['address']), $con);
	
	if(!empty($email) && !empty($password) && !empty($firstname) && !empty($lastname) && !empty($phone) && !empty($age) && !empty($address)){
		$query = "INSERT INTO Users(Email,Password,FirstName,LastName,Age,Phone,Address)
VALUES('$email', '$password', '$firstname', '$lastname', '$age', '$phone', '$address')";
		if(!mysql_query($query, $con)){
			die('Error: '.mysql_error());
		}
		else{
			$_SESSION['email']['name'] = $email;
			$_SESSION['email']['admin'] = 0;
			setcookie('email', $email, time()+3600);
			setcookie('admin', 0, time()+3600);
			$home_url = "./login.php";
			header('Location:'.$home_url);
		}	
	}
}
?>
<html lang="en">

<head>
	<script language="javascript" type="text/javascript">
	function judge(){
		var email = document.form1.email.value;
		var password = document.form1.password.value;
		var firstname = document.form1.firstname.value;
		var lastname = document.form1.lastname.value;
		var age = document.form1.age.value;
		var phone = document.form1.phone.value;
		var address = document.form1.address.value;
		var reg = "@clemson.edu$";
		var reg1 = /[A-Z]/;
		if(email==""){
			alert("Pleas type an email.");
			return false;
		}
		else if(password==""){
			alert("Pleas type a password.");
			return false;
		}
		else if(!reg1.test(password)){
			alert("Your password should include at least one capital.");
			return false;
		}
		else if(email.search(reg) < 1){
			alert("Pleas use a Clemson email to register.");
			return false;
		}
		else if(firstname==""){
			alert("Pleas type a firstname.");
			return false;
		}
		else if(lastname==""){
			alert("Pleas type a lastname.");
			return false;
		}
		else if(age==""){
			alert("Pleas type a age.");
			return false;
		}
		else if(parseInt(age)<=0){
			alert("Your age is invalid.");
			return false;
		}
		else if(phone==""){
			alert("Pleas type a phone.");
			return false;
		}
		else if(address==""){
			alert("Pleas type an address.");
			return false;
		}
		else{
			return true;
		}
	}
	</script>
    <meta http-equiv="pragma" content="no-cache" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edward Library - Registration</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign Up</h3>
                    </div>
                    <div class="panel-body">
                        <form action="./register.php" method="post" name="form1" onsubmit="return judge()">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="First Name" name="firstname" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Last Name" name="lastname" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Age" name="age" type="number" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Phone" name="phone" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Address" name="address" type="text" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
								<input type = "submit" name = "submit" class = "btn btn-lg btn-success btn-block" value="Register"></input> 
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>

