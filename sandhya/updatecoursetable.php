<?php
$n=$ns=$d=$ds=$ra=$ras="";


function namecheck()
{
	$n=trim($_POST['n']);
	$nc='/^[a-zA-Z ]*$/';
	if(preg_match($nc,$n) && strlen($n)>0)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}



function ratecheck()
{
	$ra=trim($_POST['ra']);
	$rc='/^[0-9]*$/';;
	if(preg_match($rc,$ra) && strlen($ra)>0)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}

function detailcheck()
{
	$d=trim($_POST['d']);
	if(strlen($d)>=4)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}
if(isset($_POST['s']))
{
	if(namecheck()=='y')
	{
		$n=trim($_POST['n']);
	}
	else
	{
		$ns="*** check name";
	}
	
	if(detailcheck()=='y')
	{
		$d=trim($_POST['d']);
	}
	else
	{
		$ds="*** check detail";
	}
	
	if(ratecheck()=='y')
	{
		$ra=trim($_POST['ra']);
	}
	else
	{
		$ras="*** check rate";
	}
	

if(namecheck()=='y'  &&   detailcheck()=='y' && ratecheck()=='y' )
{
//$r="name".$n."detail".$d."rate".$ra;
include"connection.php";
	//echo $r;
	$coid=$_POST['coid'];
	$cd=Date('Y-m-d');
$q="update course set 
cname='".$n."' ,cdetail='".$d."' ,crate='".$ra."' ,cdate='".$cd."' where coid='".$coid."'";


$sq=mysqli_query($cn,$q);
if($sq)
{
	echo '<script> alert("thanks for visit")</script>';
$n=$ns=$d=$ds=$ra=$ras="";
	
}
else 
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
<?php 
if(isset($_GET['coid']))
{
	$coid=$_GET['coid'];
include"connection.php";
$q="select * from course where coid='".$coid."'";
$sq=mysqli_query($cn,$q);
if($sq)
{
  if(mysqli_fetch_array($sq)>0)
  {
     $sq=mysqli_query($cn,$q);

     while($r=mysqli_fetch_array($sq))
     {
?>

<form action="" method="POST" enctype="multipart/form-data">
Course id:<input type="text" name="coid" readonly value="<?php echo $r['coid']; ?>">
<br><br> 
Course Name:<input type="text" name="n" value="<?php echo $r['cname'];?>">
<span style="color:red;"><?php echo $ns;?></span>

<br><br>

Course Detail:<textarea name="d"><?php echo $r['cdetail'];?></textarea>
<span style="color:red;"><?php echo $ds;?></span>

<br><br>

Course Fee:<input type="text" name="ra" value="<?php echo $r['crate'];?>">
<span style="color:red;"><?php echo $ras;?></span>

<br><br>


<input type="submit" name="s">
<input type="submit" name="s1" value="clear">
</form>

<?php
     }  	 
  }
  else
  {
    echo 'no record';  
  }
}
else
{
 echo'<br>'.mysqli_error($cn);
}
}
else 
{
	header("location:displaycoursetable.php");
}

?>