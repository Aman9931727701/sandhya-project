<?php 
if(isset($_GET['sid']))
{
	$sid=$_GET['sid'];
include"connection.php";
	$q="delete from student where sid='".$sid."'";
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
	header("location:displaystudenttable.php");
	}
	else 
	{
		echo '<br>'.mysqli_query($cn);
	}
}
else 
{
	header("location:displaystudenttable.php");
}

?>