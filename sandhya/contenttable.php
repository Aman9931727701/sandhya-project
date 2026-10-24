<?php 
// function to add another page code 
include"connection.php";

$q=" create table content
(ctid int(5)primary key auto_increment,
tid int(5) not null,
coid int(5) not null,
chapterno int(5) not null,
chaptername varchar(50) not null,
chaptercontent varchar(500) not null,
chaptervideo varchar(500) not null)";


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