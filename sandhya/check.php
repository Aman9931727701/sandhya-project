<?php 
if(isset($_POST['s']))
{
	$n=trim($_POST['n']);
	$p=trim($_POST['p']);
	if($n=='argus' && $p=='academy')
	{
	session_start();
	$_SESSION['a']=$n;
	$_SESSION['aid']=session_id();
	header("location:admin.php");
	}
	else 
	{
	
	
include"connection.php";
$q="select * from teacher where temail='".$n."' and tpassword='".$p."'";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
	session_start();
	$_SESSION['tt']=$n;
	$_SESSION['ttid']=session_id();
	header("location:tteacher.php");
	}
	else
	{
			
include"connection.php";
$q="select * from student where semail='".$n."' and spassword='".$p."'";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
	session_start();
	$_SESSION['ss']=$n;
	$_SESSION['ssid']=session_id();
	header("location:sstudent.php");
	}
	else
	{
		session_start();
	$_SESSION['wrong']="*** wrong id or password ";
		header("location:login.php");
	}
}
else
{
 echo '<br>'.mysqli_error($cn);
}
	}
}
else
{
 echo '<br>'.mysqli_error($cn);
}

	///
	
	}

}
else 
{	
	header("location:login.php");
}

?>