<?php
include"connection.php";
$q="select * from chat ";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);
echo '<table border="1"> <tr bgcolor="gray">
<th> chid </th> <th> tid </th> <th> sid </th> <th> message </th>
 <th> adate </th> <th> delete </th></tr>';
		while($r=mysqli_fetch_array($sq))
		{
			echo '<tr><th>'.$r['chid'].'</th>';
			echo '<th>'.$r['tid'].'</th>';
			echo '<th>'.$r['sid'].'</th>';
			echo '<th>'.$r['message'].'</th>';
			echo '<th>'.$r['adate'].'</th>';
			$chid=$r['chid'];
			echo '<th> <a href="deletechat.php?chid='.$chid.'">delete</a></th>';
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