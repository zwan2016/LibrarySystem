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

if($_SESSION['email']['admin'] == 0){
	$login_url = "./login.php";
	header('Location:'.$login_url);
}
echo $_SESSION['flag'];
if(isset($_SESSION['flag'])){
	//in case user enter through the url directly
	if(!isset($_GET['username']) || !isset($_GET['uid'])){
		$lend_url = "./userprofile.php";
		header('Location:'.$lend_url);
	}

	if(!isset($_GET['rid'])){
		if(isset($_GET['ISBN'])){
			$uid = $_GET['uid'];
			$ISBN = $_GET['ISBN'];
			$borrow = date("Y-m-d", time());
			$expired = date("Y-m-d", strtotime("$borrow +5 day"));
			require_once 'config.php';
			$sql3 = "SELECT* FROM Books WHERE ISBN = '$ISBN' AND Stock > 0";
			$sql1 = "INSERT INTO Record(ISBN, UId, BorrowTime, ExpiredTime, IsReturned) VALUES('$ISBN', '$uid', '$borrow', '$expired', 0)";
			$sql4 = "UPDATE Books SET Stock = Stock - 1 WHERE ISBN = '$ISBN'";
		$sql10 = "SELECT* FROM Record WHERE UId = '$uid' AND ISBN = '$ISBN' AND IsReturned = 0";
			$con1 =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
			if(!$con1){
				die('Could not connect: '.mysql_error());
			}
			mysql_select_db(DB_NAME, $con1);
			$stock = mysql_query($sql3, $con1);
			if(mysql_num_rows($stock) == 1){
				if(mysql_fetch_array(mysql_query($sql10, $con1))==NULL){
			mysql_query($sql1, $con1);
					mysql_query($sql4, $con1);
				}
				else{
			echo "<script>alert(\"User already has this book!\")</script>";
				}
			}
			else{
				echo "<script>alert(\"This book is not in stock!\")</script>";
			}
		}
	}
	else{
		$returnTime = date("Y-m-d", time());
		$rid = $_GET['rid'];
		require_once 'config.php';
		$con3 =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		if(!$con3){
			die('Could not connect: '.mysql_error());
		}
		mysql_select_db(DB_NAME, $con3);
		$sql6 = "SELECT ISBN FROM Record WHERE RId = '$rid'";
		$tmpdata = mysql_query($sql6, $con3);
		$tmpdata1 = mysql_fetch_array($tmpdata);
		$returnISBN = $tmpdata1['ISBN'];
		$sql9 = "SELECT* FROM Record WHERE RId = '$rid' AND IsReturned = 1";
		$returnRecord = mysql_query($sql9, $con3);
		if(mysql_num_rows($returnRecord) == 0){
			$sql5 = "UPDATE Record SET IsReturned = 1 WHERE RId = '$rid'";
			mysql_query($sql5, $con3);
			$sql11 = "UPDATE Record SET Postpone = 0 WHERE RId = '$rid'";
			mysql_query($sql11, $con3);
			//$sql8 = "UPDATE Record SET ReturnTime = '$returnTime' WHERE RId = '$rid'";
			//mysql_query($sql8, $con3);
			$sql7 = "UPDATE Books SET Stock = Stock+1 WHERE ISBN = '$returnISBN'";
			mysql_query($sql7, $con3);
		}
		else{
			echo "<script>alert(\"This book is already returned!\")</script>";
		}
	}
    $tmp1111 = $_SESSION['flag'];
    echo $tmp1111;
	unset($_SESSION['flag']);
}
require_once 'config.php';
$username = $_GET['username'];
$uid = $_GET['uid'];
$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$con){
	die('Could not connect: '.mysql_error());
}

mysql_select_db(DB_NAME, $con);
$sql = "SELECT* FROM Record LEFT JOIN Books ON Record.ISBN = Books.ISBN WHERE UId = '$uid'";
$data = mysql_query($sql, $con);
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
		var xmlHttp;
		var table;
		var entries_num;
		var type_choosed;
		var selectType;
		var indexType;
		//var entryType;
		//var indexEntry;
		var type;
		var content;
		var rid
		
		function returnBook(btn){
			//del_ff(btn);
			rid=btn.parentNode.previousSibling.previousSibling.previousSibling.previousSibling.innerHTML;
            <?php $_SESSION['flag'] = 1 ?>
            var url="userprofile1.php";
            url=url+"?username="+"<?php echo $username?>";
            url=url+"&uid="+<?php echo $uid?>;
            url=url+"&rid="+rid;
            url=url+"&sid="+Math.random();
            window.location.assign(url);
        }

        function postpone(btn){
            <?php $_SESSION['flag'] = 1 ?>
            //del_ff(btn);
            var pp;
            if(btn.innerHTML == "Approval"){
                pp = 1;
            }
            else{
                pp = 0;
            }
            rid = btn.parentNode.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.innerHTML;
            var url="userprofile1.php";
            url=url+"?username="+"<?php echo $username?>";
            url=url+"&uid="+<?php echo $uid?>;
            url=url+"&rid="+rid;
            url=url+"&pp="+pp;
            url=url+"&sid="+Math.random();
            window.location.assign(url);
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
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
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
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $username?>'s Profile</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            management users
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Record Id</th>
                                        <th>Title</th>
                                        <th>Borrow Time</th>
                                        <th>Expired Time</th>
                                        <th>Is Returned</th>
                                        <th>Postpone</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
									<?php
									$con2 =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
									if(!$con2){
										die('Could not connect: '.mysql_error());
									}
									while($row = mysql_fetch_array($data)){
										echo"<tr class=\"odd gradeX\">";
										echo"<td>".$row['RId']."</td>";
										echo"<td>".$row['Title']."</td>";
										echo"<td>".$row['BorrowTime']."</td>";
										echo"<td>".$row['ExpiredTime']."</td>";
										if($row['IsReturned'] == 1){
											echo"<td><button class=\"btn btn-warning btn-sm disabled\">Returned</button></td>";
											echo"<td><div><button class=\"btn btn-success btn-sm disabled\">Approval</button>&nbsp<button class=\"btn btn-danger btn-sm disabled\">Disapproval</button></div></td>";
										}
										else{
											echo"<td><button class=\"btn btn-warning btn-sm\" onclick=\"returnBook(this)\">Return</button></td>";
											if($row['Postpone'] == 1){
												echo"<td><div><button class=\"btn btn-success btn-sm\" onclick=\"postpone(this)\">Approval</button>&nbsp<button class=\"btn btn-danger btn-sm\" onclick=\"postpone(this)\">Disapproval</button></div></td>";
											}
											else{
												echo"<td><div><button class=\"btn btn-success btn-sm disabled\">Approval</button>&nbsp<button class=\"btn btn-danger btn-sm disabled\">Disapproval</button></div></td>";
											}
										}
										echo"</tr>";
									}
									?>
                                </tbody>
                            </table>                        
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
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
