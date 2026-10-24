<?php 
// function to add another page code 
include"connection.php";

$q=" create table student
(sid int(5)primary key auto_increment,
sname varchar(50) not null,
semail varchar(50) not null unique,
spassword varchar(50) not null,
sphone varchar(15) not null)";


// function to run query
$sq=mysqli_query($cn,$q);
if($sq)
{
	echo '<br> created ';
}
else 
{	
	// function to show sql error 
	echo '<br>'.mysqli_error($cn);
}
?>