<?php 
ob_start();
session_start();
if($_SESSION['ttid']!=session_id())
{
	header("location:login.php");
}
?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


<nav class="navbar navbar-expand-lg navbar-light bg-secondary">
  <a class="navbar-brand text-white " href="#">Explore Learning
  
  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-book-fill" viewBox="0 0 16 16">
  <path d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
</svg>
  
  
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
     
      <li class="nav-item">
        <a class="nav-link text-white" href="tteacher.php">TEACHER PROFILE</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="tcourse.php">COURSES</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="tcontent.php">CONTENT</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="tchat.php">CHAT</a>
      </li>
     <li class="nav-item">
        <a class="nav-link text-white" href="tapply.php">APPLY</a>
      </li>
 
      
     
      <li class="nav-item">
        <a class="nav-link text-white" href="end.php">LOGOUT</a>
      </li>
        
    </ul>
	<!---
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
!-->
	</div>
</nav>	
	
	
	
<style>	
a:hover{text-shadow:2px 2px 2px black;font-size:20px;}
.main1{padding-top:10px;padding-bottom:10px;top-margin:10px;
background-color:rgba(243,238,208,0.5); color:white;}

.xx{border-top:none; border-left:none;border-right:none; 
border-bottom:solid; border-size:5px;  border-color:white; 
background:transparent; outline:none; text-align:center; padding:5px;
color:white; font-size:20px;}

.xx1:hover{background-color:gray;color:white;font-size:25px;
animation:ad 2s infinite;}

@keyframes ad 
{
	0%{transform:rotate(15deg);}
	50%{transform:rotate(-15deg);}
	100%{transform:rotate(15deg);}
}
a,table{color:white;}
</style>	
	
<div class="fluid-container">	
<div class="row">	
<div class="col-md-12">	
<div class="main1">
		<center>
<?php 
$t=$_SESSION['tt'];
echo '<br> welcome '.$t;

?>

<!---- !---->
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

	//echo $r;
	$cd=Date('Y-m-d');
include"connection.php";
$q="insert into course 
(tid,cname,cdetail,crate,cdate)
values('".$_SESSION['tid']."','".$n."','".$d."','".$ra."','".$cd."')";

$sq=mysqli_query($cn,$q);
if($sq)
{
	echo '<script> alert("thanks for adding")</script>';
$n=$ns=$d=$ds=$ra=$ras="";
	
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

Course Name:<input type="text" name="n" value="<?php echo $n;?>">
<span style="color:red;"><?php echo $ns;?></span>

<br><br>

Course Detail:<textarea name="d"><?php echo $d;?></textarea>
<span style="color:red;"><?php echo $ds;?></span>

<br><br>

Course Fee:<input type="text" name="ra" value="<?php echo $ra;?>">
<span style="color:red;"><?php echo $ras;?></span>

<br><br>


<input type="submit" name="s">
<input type="submit" name="s1" value="clear">
</form>







</center>
</div>
</div>
</div>
</div>


<!---- !---->



<style>
.main{height:250px;margin-top:10px;}

.txt{position:absolute;right:5%;height:90%;top:5%;width:90%; background-color:rgba(123,234,213,0.4);text-align:center;transition:2s;}

.main:hover .txt{background-color:rgba(223,234,13,0.4);}
.main:hover .photo{transform:rotateY(180deg);}
</style>

<div class="fluid-container">
<div class="row">
<?php 
include"connection.php";
$q="select * from course where tid='".$_SESSION['tid']."'";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);

			while($r=mysqli_fetch_array($sq))
			{
			echo '<div class="col-md-4">';
						echo '<div class="main">';
											
							
							
							echo '<div class="txt">';
				echo '<br> Course id: '.$r['coid'];
				echo '<br> Teacher id: '.$r['tid'];
				echo ' <br>Course name: '.$r['cname'];
				echo ' <br>Course detail: '.$r['cdetail'];
				echo ' <br>Course fee :'.$r['crate'];
				echo ' <br>Course date: '.$r['cdate'];
				$coid=$r['coid'];
echo '<br> <a href="tcontentadd.php?coid='.$coid.'"> click to add/view content</a>';
							echo '</div>';
			
				echo '</div>';
				
				
			echo '</div>';
			}
			
	}
	else 
	{
		echo '<br> no record found ';
	}
}
else 
{
	echo '<br>'.mysqli_error($cn);
}
?>








<img src="f.jpg" style="top:0%;left:0%;width:100%; height:100%; position:fixed;
z-index:-10;">





