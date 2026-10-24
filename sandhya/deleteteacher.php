<?php 
if(isset($_GET['tid']))
{
	$tid=$_GET['tid'];
include"connection.php";
	$q="delete from teacher where tid='".$tid."'";
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
	header("location:displayteachertable.php");
	}
	else 
	{
		echo '<br>'.mysqli_query($cn);
	}
}
else 
{
	header("location:displayteachertable.php");
}

?>