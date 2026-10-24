<!---
name='/^[a-zA-Z ]*$/';
amit singh
email ='/^[a-zA-Z0-9.-_]*\@[a-zA-Z]*\.[a-zA-Z.]{2,6}$/';
amit123@gmail.com
phone ='/^[0-9]{10,10}$/;

preg_match(structure , input) 

-->
<?php 
$n=$ns=$e=$es=$p=$ps=$m=$ms=$gs=$sks=$qs=$d=$ds=$phos="";
// name check 
function namecheck() 
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

// email check 
function emailcheck() 
{
	$e=trim($_POST['e']);
	$ec='/^[a-zA-Z0-9.-_]*\@[a-zA-Z]*\.[a-zA-Z.]{2,6}$/';;
	if(preg_match($ec,$e) && strlen($e)>0 )
	{
		return 'y';
	}
	else 
	{
		return 'n';
	}
}


// phone check 
function phonecheck() 
{
	$p=trim($_POST['p']);
	$pc='/^[0-9]{10,10}$/';;
	if(preg_match($pc,$p) && strlen($p)>0 )
	{
		return 'y';
	}
	else 
	{
		return 'n';
	}
}

// message check 
function messagecheck() 
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


// gender 
function gendercheck()
{
	if(isset($_POST['g']))
	{
		return 'y';
	}
	else 
	{	
		return 'n';
	}
}

// skill 
function skillcheck()
{
	if(isset($_POST['sk']))
	{
		return 'y';
	}
	else 
	{	
		return 'n';
	}
}

// qualification
function qcheck()
{
	if($_POST['q']!='select')
	{
		return 'y';
	}
	else 
	{	
		return 'n';
	}
}

//date check
function datecheck()
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

// check photo 
function photocheck()
{
	if($_FILES['pho']!='')
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
		return 'n';
	}
} 

// full 
if(isset($_POST['s']))
{
	//name 
	if(namecheck()=='y')
	{
		$n=trim($_POST['n']);
	}
	else 
	{
		$ns="**** check name ";
	}
	
	
	//email
	if(emailcheck()=='y')
	{
		$e=trim($_POST['e']);
	}
	else 
	{
		$es="**** check email ";
	}
	
	//phone
	if(phonecheck()=='y')
	{
		$p=trim($_POST['p']);
	}
	else 
	{
		$ps="**** check phone ";
	}
	
	
	//message
	if(messagecheck()=='y')
	{
		$m=trim($_POST['m']);
	}
	else 
	{
		$ms="**** check message ";
	}
	
	//gender
	if(gendercheck()=='y')
	{
		$g=trim($_POST['g']);
	}
	else 
	{
		$gs="**** check gender ";
	}
	
	//skill
	if(skillcheck()=='y')
	{
	$sk="";
	$k=$_POST['sk'];
		foreach($k as $t)
		{
			$sk=" ".$t;
		}
	}
	else 
	{
		$sks="**** check skill ";
	}
	
	//qualification 
	if(qcheck()=='y')
	{
		$q=trim($_POST['q']);
	}
	else 
	{
		$qs="**** check qualification ";
	}
	
	//booking date
	if(datecheck()=='y')
	{
		$d=Date('Y-m-d',strtotime($_POST['d']));
	}
	else 
	{
		$ds="**** check booking ";
	}
	
	// photo 
	if(photocheck()=='n')
	{
		$phos="**** photocheck";
	}
	
	
	// full check 
	if(namecheck()=='y' && emailcheck()=='y'&& phonecheck()=='y'
	&& messagecheck()=='y'&& gendercheck()=='y'&& skillcheck()=='y'&& 
	qcheck()=='y'&& 	datecheck()=='y'&& 	photocheck()=='y')
	{
		$r="name ".$n. " email ".$e. " phone ".$p. " message ".$m. " gender ".$g. " skill ".$sk. " qualification  ".$q. " date  ".$d;
		
		echo $r;
		$fn=$_FILES['pho']['name'];
		$fa='load/'.uniqid().$fn;
		$ta=$_FILES['pho']['tmp_name'];
		move_uploaded_file($ta,$fa);
echo'<img src="'.$fa.'" width="100" height="100">';
	}
	else 
	{
		echo '<br> check ';
	}
}
?>
<form action="" method="POST" enctype="multipart/form-data" >

name:<input type="text" name="n" value="<?php echo $n; ?>"> 
<span style="color:red;"><?php echo $ns; ?></span>

<br><br>

email:<input type="text" name="e" value="<?php echo $e; ?>"> 
<span style="color:red;"><?php echo $es; ?></span>

<br><br>


phone:<input type="text" name="p" maxlength="10"value="<?php echo $p; ?>"> 
<span style="color:red;"><?php echo $ps; ?></span>
<br><br>

message:<textarea name="m"><?php echo $m; ?></textarea> 
<span style="color:red;"><?php echo $ms; ?></span>
<br><br>

gender :<input type="radio" name="g" value="male">male
<input type="radio" name="g" value="female">female
<span style="color:red;"><?php echo $gs; ?></span>
<br><br>


skill :
<input type="checkbox" name="sk[]" value="cooking">cooking
<input type="checkbox" name="sk[]" value="drinking">drinking
<span style="color:red;"><?php echo $sks; ?></span>
<br><br>


qualification 
<select name="q">
				<option value="select">select</option>
				<option value="12th">12th</option>
				<option value="10th">10th</option>
</select>
<span style="color:red;"><?php echo $qs; ?></span>
<br><br>

booking date [ within 30 days ]
<input type="date" name="d" value="<?php echo $d; ?>">
<span style="color:red;"><?php echo $ds; ?></span>
<br><br>

photo <input type="file" name="pho">
<span style="color:red;"><?php echo $phos; ?></span>
<br><br>

<input type="submit" name="s"> 
</form>
