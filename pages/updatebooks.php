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

if(isset($_SESSION['flag'])){
	if($_GET['m']==1){
		//1 means update method
		$ISBN=$_GET['ISBN'];
		$stock=$_GET['stock'];
		require_once 'config.php';
		$con1 =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		if(!$con1){
			die('Could not connect: '.mysql_error());
		}
		mysql_select_db(DB_NAME, $con1);
		$sql1 = "UPDATE Books SET Stock = '$stock' WHERE ISBN = '$ISBN'";
		mysql_query($sql1, $con1);
	}
	if($_GET['m']==0){
		//0 means delete method
		$ISBN=$_GET['ISBN'];
		require_once 'config.php';
		$con1 =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		if(!$con1){
			die('Could not connect: '.mysql_error());
		}
		mysql_select_db(DB_NAME, $con1);
		$sql1 = "DELETE FROM Books WHERE ISBN = '$ISBN'";
		mysql_query($sql1, $con1);
	}
	unset($_SESSION['flag']);
}

require_once 'config.php';
$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$con){
	die('Could not connect: '.mysql_error());
}
mysql_select_db(DB_NAME, $con);
$sql = "SELECT* FROM Books LIMIT 1000";
$data = mysql_query($sql, $con);
$sql00 = "SELECT* FROM Record LEFT JOIN Users ON Record.UId = Users.Id WHERE Postpone = 1";
$data1 = mysql_query($sql00, $con);
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
		var xmlHttp1;
		var xmlHttp2;
		var ISBN;
		var stock;
		var selectType;
		var uid;
		var username;
		
		function updateBooks(btn){
			//del_ff(btn);
			stock=btn.parentNode.parentNode.previousSibling.childNodes[0].value;
			ISBN=btn.parentNode.parentNode.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.innerHTML;
			//alert(stock+"???"+ISBN);
            xmlHttp=GetXmlHttpObject();
            if(xmlHttp==null){
                alert("Browser does not support HTTP Request");
                return;
            }
            var url="setflag.php";
            xmlHttp.onreadystatechange=function(){
				if(xmlHttp.readyState==4||xmlHttp.readyState=="complete"){
					var url="updatebooks.php";
					url=url+"?ISBN="+ISBN;
					url=url+"&stock="+stock;
					url=url+"&m="+"1";
					url=url+"&sid="+Math.random();
					window.location.assign(url);
				}
			}
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
        }
        
        function deleteBooks(btn){
			//del_ff(btn);
			ISBN=btn.parentNode.parentNode.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.innerHTML;
            xmlHttp=GetXmlHttpObject();
            if(xmlHttp==null){
                alert("Browser does not support HTTP Request")
                return;
            }
            var url="setflag.php";
            xmlHttp.onreadystatechange=function(){
				if(xmlHttp.readyState==4||xmlHttp.readyState=="complete"){
					var url="updatebooks.php";
					url=url+"?ISBN="+ISBN;
					url=url+"&m="+"0";
					url=url+"&sid="+Math.random();
					window.location.assign(url);
				}
			}
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
        }
		
		function searchBooks(){
			selectType=document.getElementById("selectType");
			//entryType=document.getElementsByName("dataTables-example_length")[0];
			indexType=selectType.selectedIndex;
			//indexEntry=entryType.selectedIndex;
			type=selectType.options[indexType].value;
			content=document.getElementById("searchInput").value;
			
			xmlHttp=GetXmlHttpObject();
			if(xmlHttp==null){
				alert("Browser does not support HTTP Request")
				return;
			}
			var url="searchBooks2.php";
			url=url+"?t="+type;
			url=url+"&c="+content;
			url=url+"&sid="+Math.random();
			xmlHttp.onreadystatechange=stateChanged;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
		
		function stateChanged(){
			if(xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){
				table.destroy();
				document.getElementsByTagName("tbody")[0].innerHTML=xmlHttp.responseText;
				table=$('#dataTables-example').DataTable({
					responsive: true
				});
				$("#dataTables-example_filter").html("<select id=\"selectType\" class=\"form-control input-sm\"><option value=\"ISBN\">ISBN</option><option value=\"Book Title\">Book Title</option><option value=\"Author\">Author</option><option value=\"Year Of Publication\">Year Of Publication</option><option value=\"Publisher\">Publisher</option></select><input id=\"searchInput\" class=\"form-control input-sm\" placeholder=\"Search...\" onkeydown=\"if\(event.keyCode==13\)searchBooks\(\)\"><button class=\"btn btn-default\" type=\"button\" onclick=\"searchBooks\(\)\"><i class=\"fa fa-search\"></i></button>");
				selectType=document.getElementById("selectType");
				//entryType=document.getElementsByName("dataTables-example_length")[0];
				selectType.options[indexType].selected=true;
				//entryType.options[indexEntry].selected=true;
			}
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
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Updating</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            update books
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ISBN</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Year Of Publication</th>
                                        <th>Publisher</th>
                                        <th>Stock</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
									<?php
									while($row = mysql_fetch_array($data)){
										echo"<tr class=\"odd gradeX\">";
										echo"<td>".$row['ISBN']."</td>";
										echo"<td>".$row['Title']."</td>";
										echo"<td>".$row['Author']."</td>";
										echo"<td>".$row['Year']."</td>";
										echo"<td>".$row['Publisher']."</td>";
										echo"<td><input value=".$row['Stock']."></input></td>";
                                        echo"<td><div><button class=\"btn btn-warning btn-sm\" onclick=\"updateBooks(this)\">Update</button><button class=\"btn btn-danger btn-sm\" onclick=\"deleteBooks(this)\">Delete</button><div></td>";
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
        $("#dataTables-example_filter").html("<select id=\"selectType\" class=\"form-control input-sm\"><option value=\"ISBN\">ISBN</option><option value=\"Book Title\">Book Title</option><option value=\"Author\">Author</option><option value=\"Year Of Publication\">Year Of Publication</option><option value=\"Publisher\">Publisher</option></select><input id=\"searchInput\" class=\"form-control input-sm\" placeholder=\"Search...\" onkeydown=\"if\(event.keyCode==13\)searchBooks\(\)\"><button class=\"btn btn-default\" type=\"button\" onclick=\"searchBooks\(\)\"><i class=\"fa fa-search\"></i></button>");
    });
    </script>

</body>

</html>


