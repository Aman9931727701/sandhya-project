<?php
$d=$ds=$a=$as="";

function amountcheck() 
{
	$a=trim($_POST['a']);
	$ac='/^[0-9]*$/';;
	if(preg_match($ac,$a) && strlen($a)>0 )
	{
		return 'y';
	}
	else 
	{
		return 'n';
	}
}

function startdatecheck()
{
	if($_POST['d']!='')
	{
		// system date 
		$sy=Date('Y');
		$sm=Date('m');
		$sd=Date('d');
		
		$sdays=($sy-1)*365 + ($sm-1)*30 +$sd;

		// enter date 
		$ey=Date('Y',strtotime($_POST['d']));
		$em=Date('m',strtotime($_POST['d']));
		$ed=Date('d',strtotime($_POST['d']));
		
		$edays=($ey-1)*365 + ($em-1)*30 +$ed;
		
		$diff=($edays-$sdays);
		if($diff>0 && $diff<=30)
		{
			return 'y';
		}
		else 
		{
			return 'n';
		}
	}
	else 
	{
		return 'n';
	}
}	


	
if(isset($_POST['s']))
{
	if(startdatecheck()=='y')
	{
		$d=Date('Y-m-d',strtotime($_POST['d']));
	}
	else 
	{
		$ds="**** check date ";
	}
	if(amountcheck()=='y')
	{
		$a=trim($_POST['a']);
	}
	else 
	{
		$as="**** check phone ";
	}
	
if(startdatecheck()=='y' && amountcheck()=='y')
{
 //$r="startdate".$d."amount".$a;
$ad=Date('Y-m-d');
$ed=date('Y-m-d', strtotime($d. ' + 365 days'));
include"connection.php";
$q="insert into apply
(tid,sid,coid,startdate,amount,adate,enddate)
values('1','1','1','".$d."','".$a."','".$ad."','".$ed."')";

$sq=mysqli_query($cn,$q);
if($sq)
{
	echo '<script> alert("thanks for apply")</script>';
$d=$ds=$a=$as="";
	
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
<form action="" method="POST" enctype="multipart/form-data">
Date:<input type="date" name="d" value="<?php echo $d; ?>">
<span style="color:red;"><?php echo $ds; ?></span>
<br><br>

amount:<input type="text" name="a" value="<?php echo $a; ?>"> 
<span style="color:red;"><?php echo $as; ?></span>
<br><br>
<input type="submit" name="s"> 
<input type="submit" name="s1" value="clear"> 

</form>

