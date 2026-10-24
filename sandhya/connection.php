<?php 
$server="localhost"; // same for all 
$user="argus1on_komal"; // same for all 
$password="Argus@123"; // mysql password 
$database="argus1on_komal"; // database name 

// function to connect to mysql & database 
$cn=mysqli_connect($server,$user,$password,$database);
if(!$cn)
{
	// function to check connection error 
	echo '<br>'.mysqli_connect_error();
	echo '<br>'.mysqli_connect_errno();
}
?>