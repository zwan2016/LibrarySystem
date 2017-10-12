<?php
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

if($_SESSION['email']['admin'] == 1){
	$login_url = "./login.php";
	header('Location:'.$login_url);
}

if(isset($_POST['submit'])){
	require_once 'config.php';
	$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	mysql_select_db(DB_NAME, $con);
	$email = $_SESSION['email']['name'];
	$firstname = mysql_real_escape_string(trim($_POST['firstname']), $con);
	$lastname = mysql_real_escape_string(trim($_POST['lastname']), $con);
	$age = mysql_real_escape_string(trim($_POST['age']), $con);
	$phone = mysql_real_escape_string(trim($_POST['phone']), $con);
	$address = mysql_real_escape_string(trim($_POST['address']), $con);
	if(!empty($firstname) && !empty($lastname) && !empty($age) && !empty($phone) && !empty($address)){
		$query = "UPDATE Users SET FirstName = '$firstname', LastName = '$lastname', Age = '$age', Phone = '$phone', Address = '$address' WHERE Email = '$email'";
		mysql_query($query, $con);	
	}
}

require_once 'config.php';
$username = $_SESSION['email']['name'];
$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$con){
	die('Could not connect: '.mysql_error());
}

mysql_select_db(DB_NAME, $con);
$sql1 = "SELECT* FROM Users WHERE Email = '$username'";
//get information for the user himself
$tmp = mysql_query($sql1, $con);
$tmp1 = mysql_fetch_array($tmp);
$uid = $tmp1['Id'];
?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edward Libaray System</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript">
		function judge(){
		var firstname = document.form1.firstname.value;
		var lastname = document.form1.lastname.value;
		var age = document.form1.age.value;
		var phone = document.form1.phone.value;
		var address = document.form1.address.value;
		if(firstname==""){
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

</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="lend.php">Welcome! <?php echo $_SESSION['email']['name'] ?></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['email']['name'] ?></a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
							<a href="myprofile.php"><i class="fa fa-bar-chart-o fa-fw"></i> My Profile</a>
                        </li>
                        <li>
                            <a href="usersearch.php"><i class="fa fa-table fa-fw"></i> Search Books</a>
                        </li>
                        <li>
                            <a href="updateinformation.php"><i class="fa fa-edit fa-fw"></i> Update Information</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
             <div class="container">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="login-panel panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Update your information</h3>
							</div>
							<div class="panel-body">
								<form action="./updateinformation.php" method="post" name="form1" onsubmit="return judge()">
									<fieldset>
										<div class="form-group">
											FirstName<input class="form-control" placeholder="First Name" name="firstname" type="text" value="<?php echo $tmp1['FirstName'] ?>">
										</div>
										<div class="form-group">
											LastName<input class="form-control" placeholder="Last Name" name="lastname" type="text" value="<?php echo $tmp1['LastName'] ?>">
										</div>
										<div class="form-group">
											Age<input class="form-control" placeholder="Age" name="age" type="number" value=<?php echo $tmp1['Age'] ?>>
										</div>
										<div class="form-group">
											Phone Number<input class="form-control" placeholder="Phone" name="phone" type="text" value="<?php echo $tmp1['Phone'] ?>">
										</div>
										<div class="form-group">
											Address<input class="form-control" placeholder="Address" name="address" type="text" value="<?php echo $tmp1['Address'] ?>">
										</div>
										<!-- Change this to a button or input when using this as a form -->
										<input type = "submit" name = "submit" class = "btn btn-lg btn-success btn-block" value="Update"></input> 
									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        table=$('#dataTables-example').DataTable({
            responsive: true
        });
        $("#dataTables-example_filter").html("");
    });
    </script>

</body>

</html>
