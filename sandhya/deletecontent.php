<?php 
if(isset($_GET['ctid']))
{
	$ctid=$_GET['ctid'];
include"connection.php";
	$q="delete from content where ctid='".$ctid."'";
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
	header("location:displaycontenttable.php");
	}
	else 
	{
		echo '<br>'.mysqli_query($cn);
	}
}
else 
{
	header("location:displaycontenttable.php");
}

?>