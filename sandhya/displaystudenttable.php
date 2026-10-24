<?php
include"connection.php";
$q="select * from student ";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);
echo '<table border="1"> <tr bgcolor="blue">
<th> sid </th> <th> sname </th> <th> semail </th>
<th> spassword  </th> <th> sphone </th> <th> delete </th> </tr>';
		while($r=mysqli_fetch_array($sq))
		{
			echo '<tr><th>'.$r['sid'].'</th>';
			echo '<th>'.$r['sname'].'</th>';
			echo '<th>'.$r['semail'].'</th>';
			echo '<th>'.$r['spassword'].'</th>';
			echo '<th>'.$r['sphone'].'</th>';
			$sid=$r['sid'];
			echo '<th> <a href="deletestudent.php?sid='.$sid.'">delete</a></th>';
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