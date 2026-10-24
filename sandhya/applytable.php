<?php 
// function to add another page code 
include"connection.php";

$q=" create table apply
(aid int(5)primary key auto_increment,
tid int(5) not null,
sid int(5) not null,
coid int(5) not null,
startdate date not null,
enddate date not null,
amount int(8) not null,
adate date not null)";


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