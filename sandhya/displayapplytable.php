<?php
include"connection.php";
$q="select * from apply";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);
echo '<table border="1"> <tr bgcolor="gray">
<th> aid </th> <th> tid </th><th> sid </th><th> coid </th><th> startdate </th> <th> enddate </th>
<th> amount  </th> <th>adate</th> <th> delete </th>  </tr>';
		while($r=mysqli_fetch_array($sq))
		{
			echo '<tr><th>'.$r['aid'].'</th>';
			echo '<th>'.$r['tid'].'</th>';
			echo '<th>'.$r['sid'].'</th>';
			echo '<th>'.$r['coid'].'</th>';
			echo '<th>'.$r['startdate'].'</th>';
			echo '<th>'.$r['enddate'].'</th>';
			echo '<th>'.$r['amount'].'</th>';
			echo '<th>'.$r['adate'].'</th>';
			$aid=$r['aid'];
			echo '<th> <a href="deleteapply.php?aid='.$aid.'">delete</a></th>';
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