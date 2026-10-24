<?php
include"connection.php";
$q="select * from course ";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);
echo '<table border="1"> <tr bgcolor="gray">
<th> coid </th> <th> tid </th> <th> cname </th> <th> cdetail </th>
<th> crate </th> <th> cdate </th> <th> delete </th> <th> update </th></tr>';
		while($r=mysqli_fetch_array($sq))
		{
			echo '<tr><th>'.$r['coid'].'</th>';
			echo '<th>'.$r['tid'].'</th>';
			echo '<th>'.$r['cname'].'</th>';
			echo '<th>'.$r['cdetail'].'</th>';
			echo '<th>'.$r['crate'].'</th>';
			echo '<th>'.$r['cdate'].'</th>';
			$coid=$r['coid'];
			echo '<th> <a href="deletecourse.php?coid='.$coid.'">delete</a></th>';
			echo '<th> <a href="updatecoursetable.php?coid='.$coid.'">update</a></th>';
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