<?php
$type=$_GET["t"];
$content=$_GET["c"];
require_once 'config.php';
$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$con){
	die('Could not connect: '.mysql_error());
}

mysql_select_db(DB_NAME, $con);
if($type=="Email"){
	$sql = "SELECT* FROM Users WHERE Email LIKE '%".$content."%' AND Is_librian=0";
}
if($type=="Id"){
	$sql = "SELECT* FROM Users WHERE Id LIKE '%".$content."%'";
}
$data = mysql_query($sql, $con);
if($data==null){
	return;
}
while($row = mysql_fetch_array($data)){
	echo"<tr class=\"odd gradeX\">";
	echo"<td>".$row['Id']."</td>";
	echo"<td name=\"user\">".$row['Email']."</td>";
    echo"<td><input type= \"submit\" name = \"setuser\" class = \"btn btn-warning btn-sm\" value=\"Select\" onclick=\"setUser(this)\"></input></td>";
	echo"</tr>";
}
mysql_close($con);

?>
