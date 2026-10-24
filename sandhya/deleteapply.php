<?php 
if(isset($_GET['aid']))
{
	$aid=$_GET['aid'];
include"connection.php";
	$q="delete from apply where aid='".$aid."'";
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
	header("location:displayapplytable.php");
	}
	else 
	{
		echo '<br>'.mysqli_query($cn);
	}
}
else 
{
	header("location:displayapplytable.php");
}

?>