<?php 
// function to add another page code 
include"connection.php";

$q=" create table course
(coid int(5)primary key auto_increment,
tid int(5) not null,
cname varchar(50) not null,
cdetail varchar(500) not null,
crate   int(6) not null,
cdate date not null)";


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