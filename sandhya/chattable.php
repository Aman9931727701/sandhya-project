<?php 
// function to add another page code 
include"connection.php";

$q=" create table chat
(chid int(5)primary key auto_increment,
senderid int(5) not null,
receiverid int(5) not null,
message varchar(500) not null,
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