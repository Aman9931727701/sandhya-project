<?php 
if(isset($_GET['coid']))
{
	$coid=$_GET['coid'];
include"connection.php";
	$q="delete from course where coid='".$coid."'";
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
	header("location:displaycoursetable.php");
	}
	else 
	{
		echo '<br>'.mysqli_query($cn);
	}
}
else 
{
	header("location:displaycoursetable.php");
}

?>