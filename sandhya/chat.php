<?php
$m=$ms="";
function messagecheck()
{
  $m=trim($_POST['m']);
  if(strlen($m)>=4)
  { 
    return'y';
  }
  else
  {
    return'n';
  }
}
if(isset($_POST['s']))
{
if(messagecheck()=='y')
  {
    $m=trim($_POST['m']);
  }
  else
  {
    $ms="**** check message ";
  }
  if(messagecheck()=='y')
{
  //$r="message".$m;
  //echo '<br> '.$r;
$d=Date('Y-m-d');
include"connection.php";
$q="insert into chat
(tid,sid,message,adate)
values('1','1','".$m."','".$d."')";

$sq=mysqli_query($cn,$q);
if($sq)
{
	echo '<script> alert("thanks for contact")</script>';
$m=$ms="";
	
}else 
{	
	// function to show sql error 
	echo '<br>'.mysqli_error($cn);
}

}
	else
	{
		echo '<script> alert("check")</script>';
	}
}
?>
<form action=""method="POST"enctype="multipart/form-date">
message:<textarea name="m"><?php echo $m;?></textarea>
<span style="color:red;"><?php echo $ms;?></span>

<br><br>
<input type="submit" name="s">
<input type="submit" name="s1" value="clear">
</form>