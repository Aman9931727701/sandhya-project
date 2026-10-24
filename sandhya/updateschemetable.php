<?php 
$n=$ns=$e=$es=$p=$ps=$m=$ms=$sks=$qs=$d=$ds=$phos=$d1=$ds1="";
 
function schemenamecheck() 
{
	$n=trim($_POST['n']);
	$nc='/^[a-zA-Z ]*$/';
	if(preg_match($nc,$n) && strlen($n)>0 )
	{
		return 'y';
	}
	else 
	{
		return 'n';
	}
}
 

function schemedetailcheck() 
{
	$m=trim($_POST['m']);
	
	if( strlen($m)>=4 )
	{
		return 'y';
	}
	else 
	{
		return 'n';
	}
}



function sdatecheck()
{
	if($_POST['d']!='')
	{
		
		$sy=Date('Y');
		$sm=Date('m');
		$sd=Date('d');
		
		$sdays=($sy-1)*365 + ($sm-1)*30 +$sd;

		
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
function edatecheck()
{
	if($_POST['d1']!='')
	{
		
		$sy=Date('Y',strtotime($_POST['d']));
		$sm=Date('m',strtotime($_POST['d']));
		$sd=Date('d',strtotime($_POST['d']));
		
		$sdays=($sy-1)*365 + ($sm-1)*30 +$sd;

		
		$ey=Date('Y',strtotime($_POST['d1']));
		$em=Date('m',strtotime($_POST['d1']));
		$ed=Date('d',strtotime($_POST['d1']));
		
		$edays=($ey-1)*365 + ($em-1)*30 +$ed;
		
		$diff=($edays-$sdays);
		if($diff>0 && $diff<=20)
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

function photocheck()
{
	if($_FILES['pho']['name']!='')
	{
		$fn=$_FILES['pho']['name'];
		$ln=strlen($fn);
		$dot=strrpos($fn,".");
		$cut=substr($fn,$dot+1,$ln);
		$ext=array('jpg','png','bmp','JPG','jpeg','jfif');
	if(in_array($cut,$ext))
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
		return 'yy';
	}
} 


if(isset($_POST['s']))
{
	if(schemenamecheck()=='y')
	{
		$n=trim($_POST['n']);
	}
	else 
	{
		$ns="**** check schemename ";
	}
		
	if(schemedetailcheck()=='y')
	{
		$m=trim($_POST['m']);
	}
	else 
	{
		$ms="**** check scheme detail ";
	}
	
			
	
	if(sdatecheck()=='y')
	{
		$d=Date('Y-m-d',strtotime($_POST['d']));
	}
	else 
	{
		$ds="**** check date ";
	}
	if(edatecheck()=='y')
	{
		$ed=Date('Y-m-d',strtotime($_POST['d1']));
	}
	else 
	{
		$ds1="**** check end date ";
	}
	

	if(photocheck()=='n')
	{
		$phos="**** photocheck";
	}
	
	
	
	if(schemenamecheck()=='y' && schemedetailcheck()=='y'&&  	sdatecheck()=='y'&& edatecheck()=='y' )
	{
		//$r="schemename ".$n.  " schemedetail ".$m. " start date  ".$d. " end date  ".$ed;
		
		//echo $r;
$schemeid=$_POST['schemeid'];
$dt=date('Y-m-d');
		if(photocheck()=='y')
{
		$fn=$_FILES['pho']['name'];
		$fa='load/'.uniqid().$fn;
		$ta=$_FILES['pho']['tmp_name'];
		move_uploaded_file($ta,$fa);
//echo'<img src="'.$fa.'" width="100" height="100">';
//  kam start 


$q="update  scheme  set 
schemename='".$n."' ,schemedetail='".$m."' ,startdate='".$d."', enddate='".$ed."'  ,photo='".$fa."',insertdate='".$dt."' where schemeid='".$schemeid."'";
}
else if(photocheck()=='yy')
{
$q="update  scheme  set 
schemename='".$n."' ,schemedetail='".$m."' ,startdate='".$d."', enddate='".$ed."'  ,insertdate='".$dt."' where schemeid='".$schemeid."'";
}

include"connection.php";
$sq=mysqli_query($cn,$q);
if($sq)
{
	echo '<script> alert("content added")</script>';
$n=$ns=$e=$es=$p=$ps=$m=$ms=$gs=$sks=$qs=$d=$ds=$phos=$pa=$pas="";
	
}
else 
{
	echo '<br>'.mysqli_error($cn);
}

// kam end 

	}
	else 
	{
		echo '<br> check ';
	}
}
?>

<?php 
if(isset($_GET['schemeid']))
{
	$schemeid=$_GET['schemeid'];
include"connection.php";
$q="select * from scheme where schemeid='".$schemeid."'";
$sq=mysqli_query($cn,$q);
if($sq)
{
  if(mysqli_fetch_array($sq)>0)
  {
     $sq=mysqli_query($cn,$q);

     while($r=mysqli_fetch_array($sq))
     {
	 ?>
	 
	 
<form action="" method="POST" enctype="multipart/form-data" >


Scheme id:<input type="text" name="schemeid" readonly value="<?php echo $r['schemeid']; ?>">
<br><br> 
Scheme name:<input type="text" name="n" value="<?php echo $r['schemename']; ?>"> 
<span style="color:red;"><?php echo $ns; ?></span>

<br><br>

Scheme details:<textarea name="m"><?php echo $r['schemedetail']; ?></textarea> 
<span style="color:red;"><?php echo $ms; ?></span>
<br><br>



Start date
<input type="date" name="d" value="<?php echo $r['startdate']; ?>">
<span style="color:red;"><?php echo $ds; ?></span>
<br><br>

End date 
<input type="date" name="d1" value="<?php echo $r['enddate']; ?>">
<span style="color:red;"><?php echo $ds1; ?></span>
<br><br>

Photo <input type="file" name="pho">
<span style="color:red;"><?php echo $phos; ?></span>
<th><img src="<?php echo $r['photo'] ;?>" width="100" height="100">
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
	header("location:displayschemeformtable.php");
}

?>