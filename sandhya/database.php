<?php 
$server="localhost"; // same for all 
$user="root"; // same for all 
$password="KOM#79anu23"; // mysql password 

// function to connect to mysql 
$cn=mysqli_connect($server,$user,$password);
if($cn)
{
	$q="create database komal";
	// function to run mysql query 
	$sq=mysqli_query($cn,$q);
	if($sq)
	{
			echo '<br> created';
	}
	else 
	{
		// function to show sql error sql 
		echo '<br>'.mysqli_error($cn);
	}
}
else 
{
	// function to check connection error 
	echo '<br>'.mysqli_connect_error();
	echo '<br>'.mysqli_connect_errno();
}
?>