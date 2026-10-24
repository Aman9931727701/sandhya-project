<?php 
if(isset($_GET['chid']))
{
	$chid=$_GET['chid'];
include"connection.php";
	$q="delete from chat where chid='".$chid."'";
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
	header("location:displaychattable.php");
	}
	else 
	{
		echo '<br>'.mysqli_query($cn);
	}
}
else 
{
	header("location:displaychattable.php");
}

?>