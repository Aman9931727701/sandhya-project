<?php
include"connection.php";
$q="select * from teacher ";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);
echo '<table border="1"> <tr bgcolor="blue">
<th> tid </th> <th> tname </th> <th> temail </th>
<th> tpassword  </th> <th> tphone </th> <th> tdetail </th> <th> delete</th> </tr>';
		while($r=mysqli_fetch_array($sq))
		{
			echo '<tr><th>'.$r['tid'].'</th>';
			echo '<th>'.$r['tname'].'</th>';
			echo '<th>'.$r['temail'].'</th>';
			echo '<th>'.$r['tpassword'].'</th>';
			echo '<th>'.$r['tphone'].'</th>';
			echo '<th>'.$r['tdetail'].'</th>';
			$tid=$r['tid'];
			echo '<th> <a href="deleteteacher.php?tid='.$tid.'">delete</a></th>';
			echo '</tr>';
		}
		echo '</table>';
	}
	else
	{
		echo '<br> no record found';
	}
}
else
{
 echo '<br>'.mysqli_error($cn);
}


?>