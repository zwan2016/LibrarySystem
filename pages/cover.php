<?php
session_start();
require_once 'config.php';
$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$con){
	die('Could not connect: '.mysql_error());
}
mysql_select_db(DB_NAME, $con);
//$dir=$_POST['select'];

//$filepath="/home/zwan/public_html/6620/backup/".$dir;
$filepath="/home/zwan/public_html/6620/backup";
if($_POST['select1']=="All"){
	$record=file_get_contents($filepath."/Record.sql");
	$segments=explode(";", $record);
	foreach($segments as $segment){
		//excute each query in Record.sql
		mysql_query($segment, $con);
	}
	$users=file_get_contents($filepath."/Users.sql");
	$segments=explode(";", $users);
	foreach($segments as $segment){
		//excute each query in Users.sql
		mysql_query($segment, $con);
	}
	$books=file_get_contents($filepath."/Books.sql");
	$segments=explode(";", $books);
	foreach($segments as $segment){
		//excute each query in Books.sql
		mysql_query($segment, $con);
	}
}

if($_POST['select1']=="Record.sql"){
  echo "test";
	$record=file_get_contents($filepath."/Record.sql");
	$segments=explode(";", $record);
	foreach($segments as $segment){
		//excute each query in Record.sql
		mysql_query($segment, $con);
	}
}

if($_POST['select1']=="Books.sql"){
	$books=file_get_contents($filepath."/Books.sql");
	$segments=explode(";", $books);
	foreach($segments as $segment){
		//excute each query in Books.sql
		mysql_query($segment, $con);
	}
}

if($_POST['select1']=="Users.sql"){
	$users=file_get_contents($filepath."/Users.sql");
	$segments=explode(";", $users);
	foreach($segments as $segment){
		//excute each query in Users.sql
		mysql_query($segment, $con);
	}
}
$url = "./backup.php";
header('Location:'.$url);
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


