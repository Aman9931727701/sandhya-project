<?php
include"connection.php";
$q="select * from enquiry ";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);
echo '<table border="1"> <tr bgcolor="blue">
<th> eid </th> <th> name </th> <th> email </th>
<th> phone </th> <th> message </th> <th> date <th> delete </th> </tr>';
		while($r=mysqli_fetch_array($sq))
		{
			echo '<tr><th>'.$r['eid'].'</th>';
			echo '<th>'.$r['name'].'</th>';
			echo '<th>'.$r['email'].'</th>';
			echo '<th>'.$r['phone'].'</th>';
			echo '<th>'.$r['message'].'</th>';
			echo '<th>'.$r['date'].'</th>';
			$eid=$r['eid'];
			echo '<th> <a href="deleteenquiry.php?eid='.$eid.'">delete</a></th>';
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