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

if(isset($_SESSION['flag'])){
	//we set this flag in case every time we refresh the page then we do a 'get method'
	if(isset($_GET['rid'])){
		require_once 'config.php';
		$con1 =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		if(!$con1){
			die('Could not connect: '.mysql_error());
		}

		mysql_select_db(DB_NAME, $con1);
		$rid = $_GET['rid'];
		$sql2 = "UPDATE Record SET Postpone = 1 WHERE RId = '$rid'";
		mysql_query($sql2, $con1);
	}
	unset($_SESSION['flag']);
}


require_once 'config.php';
$username = $_SESSION['email']['name'];
$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$con){
	die('Could not connect: '.mysql_error());
}

mysql_select_db(DB_NAME, $con);
$sql1 = "SELECT* FROM Users WHERE Email = '$username'";
//show user's profile to himself
$tmp = mysql_query($sql1, $con);
$tmp1 = mysql_fetch_array($tmp);
$uid = $tmp1['Id'];
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
		var rid
		function request(btn){
			//del_ff(btn);
			rid=btn.parentNode.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.innerHTML;    
			xmlHttp=GetXmlHttpObject();
            if(xmlHttp==null){
                alert("Browser does not support HTTP Request")
                return;
            }
            var url="setflag.php";
            xmlHttp.onreadystatechange=function(){
				if(xmlHttp.readyState==4||xmlHttp.readyState=="complete"){
					var url="myprofile.php";
					url=url+"?rid="+rid;
					url=url+"&sid="+Math.random();
					window.location.assign(url);
				}
			}
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null); 
        }
        
        function GetXmlHttpObject(){
            var xmlHttp=null;
            try
            {
                // Firefox, Opera 8.0+, Safari
                xmlHttp=new XMLHttpRequest();
            }
            catch (e)
            {
                //Internet Explorer
                try
                {
                    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
            }
            return xmlHttp;
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
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">My Profile</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            management records
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
											echo"<td>Returned</td>";
										}
										else{
											echo"<td>Not returned</td>";
										}
										if($row['IsReturned'] == 0 && $row['Postpone'] == 0){
											echo"<td><button class=\"btn btn-primary\" onclick=\"request(this)\">Request</button></td>";
										}
										else{
											echo"<td><button class=\"btn btn-primary disabled\">Request</button></td>";
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
