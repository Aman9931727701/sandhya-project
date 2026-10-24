<?php 
// function to add another page code 
include"connection.php";

$q=" create table enquiry 
(eid int(5)primary key auto_increment,
name varchar(50) not null,
email varchar(50) not null,
phone varchar(50) not null,
message varchar(500) not null,
date  date not null)";

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