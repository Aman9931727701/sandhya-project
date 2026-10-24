<?php
include"connection.php";
$q="select * from content ";
$sq=mysqli_query($cn,$q);
if($sq)
{
	if(mysqli_fetch_array($sq)>0)
	{
		$sq=mysqli_query($cn,$q);
echo '<table border="1"> <tr bgcolor="blue">
<th> ctid </th> <th> tid </th> <th> coid </th>
<th> chapterno </th> <th> chaptername </th>
<th>chaptercontent </th> <th> chaptervideo</th><th> delete </th> </tr>';
		while($r=mysqli_fetch_array($sq))
		{
			echo '<tr><th>'.$r['ctid'].'</th>';
			echo '<th>'.$r['tid'].'</th>';
			echo '<th>'.$r['coid'].'</th>';
			echo '<th>'.$r['chapterno'].'</th>';
			echo '<th>'.$r['chaptername'].'</th>';
			echo '<th><iframe src="'.$r['chaptercontent'].'" width="100" height="100"></iframe></th>';
			echo '<th><iframe width="200" height="150" src="'.$r['chaptervideo'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></th>';
			$ctid=$r['ctid'];
			echo '<th> <a href="deletecontent.php?ctid='.$ctid.'">delete</a></th>';
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