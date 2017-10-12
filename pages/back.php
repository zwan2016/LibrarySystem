<?php
session_start();
if(isset($_POST['submit'])){
	require_once 'config.php';
	$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$con){
		die('Could not connect: '.mysql_error());
	}
	mysql_select_db(DB_NAME, $con);
	$sql = "SELECT* FROM Users";
	$data = mysql_query($sql, $con);
	$sql1 = "SELECT* FROM Books";
	$data1 = mysql_query($sql1, $con);
	$sql2 = "SELECT* FROM Record";
	$data2 = mysql_query($sql2, $con);
	$date = new DateTime();
	//$result = date_format($date, "Y-m-d-H-i-s");
	//$filepath = "../backup/".$result;
  $filepath = "../backup";
	mkdir($filepath);
  if($_POST['select2']=="Users"){
  	$userRes = fopen($filepath."/Users.sql", "w+");
  	fwrite($userRes,"USE Wan_0c49;\n");
  	fwrite($userRes,"truncate table Users;\n");
  	while($row = mysql_fetch_array($data)){
  		//create Users.sql
  		fwrite($userRes,"INSERT INTO Users VALUES (".$row['Id'].",'".$row['Email']."','".$row['Password']."',".$row['Is_librian'].",'".$row['FirstName']."','".$row['LastName']."',".$row['Age'].",'".$row['Phone']."','".$row['Address']."');\n");
  	}
  	fclose($userRes);
  }
  if($_POST['select2']=="Books"){
  	$bookRes = fopen($filepath."/Books.sql", "w+");
  	fwrite($bookRes,"USE Wan_0c49;\n");
  	fwrite($bookRes,"truncate table Books;\n");
  	while($row = mysql_fetch_array($data1)){
  		//create Books.sql
  		fwrite($bookRes,"INSERT INTO Books VALUES ('".$row['ISBN']."','".$row['Title']."','".$row['Author']."',".$row['Year'].",'".$row['Publisher']."',".$row['Stock'].");\n");
  	}
  	fclose($bookRes);
  }
  if($_POST['select2']=="Record"){
  	$recordRes = fopen($filepath."/Record.sql", "w+");
  	fwrite($recordRes,"USE Wan_0c49;\n");
  	fwrite($recordRes,"truncate table Record;\n");
  	while($row = mysql_fetch_array($data2)){
  		//create Record.sql
  		fwrite($recordRes,"INSERT INTO Record VALUES (".$row['RId'].",'".$row['ISBN']."',".$row['UId'].",'".$row['BorrowTime']."','".$row['ExpiredTime']."','".$row['ReturnTime']."',".$row['IsReturned'].",".$row['Postpone'].");\n");
  	}
  	fclose($recordRes);
  }
	$url = "./backup.php";
	header('Location:'.$url);
}
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
		function backup(){
			//alert("Backup successful!");
			return true;
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
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <?php
						while($row1 = mysql_fetch_array($data1)){
							$url = "userprofile1.php?username=".$row1['Email']."&uid=".$row1['UId'];
							echo"<li><a href=\"".$url."\"><div><i class=\"fa fa-envelope fa-fw\"></i>";
							echo $row1['Email'];
							echo"<span class=\"pull-right text-muted small\">Postpone</span></div></a></li>";
							echo"<li class=\"divider\"></li>";
						}
						?> 
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
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
                            <a href="lend.php"><i class="fa fa-dashboard fa-fw"></i> Lend Books</a>
                        </li>
                        <li>
                            <a href="userprofile.php"><i class="fa fa-bar-chart-o fa-fw"></i> Users Profile</a>
                        </li>
                        <li>
                            <a href="managebook.php"><i class="fa fa-table fa-fw"></i> Management Books</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="updatebooks.php">Update</a>
                                </li>
                                <li>
                                    <a href="addbooks.php">Add</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="backup.php"><i class="fa fa-files-o fa-fw"></i> Backup Database</a>
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
								<h3 class="panel-title">Wait for seconds....</h3>
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
        $("#dataTables-example_filter").html("<select id=\"selectType\" class=\"form-control input-sm\"><option value=\"ISBN\">ISBN</option><option value=\"Book Title\">Book Title</option><option value=\"Author\">Author</option><option value=\"Year Of Publication\">Year Of Publication</option><option value=\"Publisher\">Publisher</option></select><input id=\"searchInput\" class=\"form-control input-sm\" placeholder=\"Search...\" onkeydown=\"if\(event.keyCode==13\)searchBooks\(\)\"><button class=\"btn btn-default\" type=\"button\" onclick=\"searchBooks\(\)\"><i class=\"fa fa-search\"></i></button>");
    });
    </script>

</body>

</html>



