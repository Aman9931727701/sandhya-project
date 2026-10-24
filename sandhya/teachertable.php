<?php 
// function to add another page code 
include"connection.php";

$q=" create table teacher 
(tid int(5)primary key auto_increment,
tname varchar(50) not null,
temail varchar(50) not null unique,
tpassword varchar(50) not null,
tphone varchar(15) not null,
tdetail varchar(500) not null)";

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