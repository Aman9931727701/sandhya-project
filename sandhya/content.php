<?php
$cn=$cns=$d=$ds=$c=$cons=$cs=$con=$pho=$cc=$v=$vs="";



function chapternocheck()
{
	$c=trim($_POST['c']);
	$cc='/^[0-9]*$/';;
	if(preg_match($cc,$c) && strlen($c)>0)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}


function chapnamecheck()
{
	$cp=trim($_POST['cn']);
	$cnc='/^[a-zA-Z ]*$/';
	if(preg_match($cnc,$cp) && strlen($cp)>0)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}

function contentcheck()
{
	if($_FILES['con']!='')
	{
		$fn=$_FILES['con']['name'];
		$ln=strlen($fn);
		$dot=strrpos($fn,".");
		$cut=substr($fn,$dot+1,$ln);
		if($cut=='pdf')
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

function videocheck()
{
	$v=trim($_POST['v']);
	if(strlen($v)>=4)
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
	if(chapnamecheck()=='y')
	{
		$cp=trim($_POST['cn']);
	}
	else
	{
		$cns="*** check name";
	}
	
	if(contentcheck()=='n')
	{
		$con="**** contentcheck";
	}
	
	
	if(chapternocheck()=='y')
	{
		$c=trim($_POST['c']);
	}
	else
	{
		$cs="*** check chapter no";
	}
	if(videocheck()=='y')
	{
		$v=trim($_POST['v']);
	}
	else
	{
		$vs="*** check message";
	}
	

if(chapternocheck()=='y' && chapnamecheck()=='y'  &&   contentcheck()=='y' && videocheck()=='y')
{
//$r="no".$c."name".$cp."content".$d."video".$v;

	//echo $r;
	
		$fn=$_FILES['con']['name'];
		$fa='load/'.uniqid().$fn;
		$ta=$_FILES['con']['tmp_name'];
		move_uploaded_file($ta,$fa);
//echo'<iframe src="'.$fa.'" width="300" height="300"></iframe>';
include"connection.php";
$q="insert into content
(tid,coid,chapterno,chaptername,chaptercontent,chaptervideo)
values('1','1','".$c."','".$cp."','".$fa."','".$v."')";

$sq=mysqli_query($cn,$q);
if($sq)
{
	echo '<script> alert("content added")</script>';
$cn=$cns=$d=$ds=$c=$cons=$cs=$con=$pho=$cc=$v=$vs="";
	
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

Chapter no:<input type="text" name="c" value="<?php echo $c;?>">
<span style="color:red;"><?php echo $cs;?></span>

<br><br>
Chapter Name:<input type="text" name="cn" value="<?php echo $cn;?>">
<span style="color:red;"><?php echo $cns;?></span> 

<br><br>

Chapter Content:<input type="file" name="con">
<span style="color:red;"><?php echo $cons; ?></span>
<br><br>
Chapter video:<textarea name="v"><?php echo $v;?></textarea>
<span style="color:red;"><?php echo $vs;?></span>


<br><br>


<input type="submit" name="s">
<input type="submit" name="s1" value="clear">
</form>





