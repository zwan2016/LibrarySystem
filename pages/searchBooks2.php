<?php
$type=$_GET["t"];
$content=$_GET["c"];
require_once 'config.php';
$con =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$con){
	die('Could not connect: '.mysql_error());
}

mysql_select_db(DB_NAME, $con);
if($type=="ISBN"){
	$sql = "SELECT* FROM Books WHERE ISBN LIKE '%".$content."%'";
}
if($type=="Book Title"){
	$sql = "SELECT* FROM Books WHERE Title LIKE '%".$content."%'";
}
if($type=="Author"){
	$sql = "SELECT* FROM Books WHERE Author LIKE '%".$content."%'";
}
if($type=="Year Of Publication"){
	$sql = "SELECT* FROM Books WHERE Year LIKE '%".$content."%'";
}
if($type=="Publisher"){
	$sql = "SELECT* FROM Books WHERE Publisher = '".$content."'";
}
$data = mysql_query($sql, $con);
if($data==null){
	return;
}
while($row = mysql_fetch_array($data)){
	echo"<form>";
	echo"<tr class=\"odd gradeX\">";
	echo"<td>".$row['ISBN']."</td>";
	echo"<td>".$row['Title']."</td>";
	echo"<td>".$row['Author']."</td>";
	echo"<td>".$row['Year']."</td>";
	echo"<td>".$row['Publisher']."</td>";
	echo"<td><input value=".$row['Stock']."></input></td>";
    echo"<td><div><button class=\"btn btn-warning btn-sm\" onclick=\"updateBooks(this)\">Update</button><button class=\"btn btn-danger btn-sm\" onclick=\"deleteBooks(this)\">Delete</button><div></td>";
	echo"</tr>";
	echo"</form>";
}
mysql_close($con);

?>
