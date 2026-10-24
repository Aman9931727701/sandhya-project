<?php 
if(isset($_GET['eid']))
{
	$eid=$_GET['eid'];
include"connection.php";
	$q="delete from enquiry where eid='".$eid."'";
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
	header("location:displayenquirytable.php");
	}
	else 
	{
		echo '<br>'.mysqli_query($cn);
	}
}
else 
{
	header("location:displayenquirytable.php");
}

?>