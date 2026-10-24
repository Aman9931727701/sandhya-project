<?php
include"connection.php";
$q="select * from scheme ";
$sq=mysqli_query($cn,$q);
if($sq)
{
  if(mysqli_fetch_array($sq)>0)
  {
     $sq=mysqli_query($cn,$q);
echo '<table border="1"><tr bgcolor="red"><th> schemeid </th><th> schemename </th> <th> schemedetail </th><th> startdate </th><th> enddate </th> <th> photo </th> <th> delete </th><th> update </th></tr>';
     while($r=mysqli_fetch_array($sq))
     {
       echo '<tr><th>'.$r['schemeid'].'</th>'; 
       echo '<th>'.$r['schemename'].'</th>'; 
       echo '<th>'.$r['schemedetail'].'</th>'; 
       echo '<th>'.$r['startdate'].'</th>'; 
       echo '<th>'.$r['enddate'].'</th>'; 
       echo '<th><img src="'.$r['photo'].'" width="100" height="100"></th>'; 
	   $schemeid=$r['schemeid'];
	   echo '<th> <a href="deleteschemetable.php?schemeid='.$schemeid.'">delete</a></th>';
	   echo '<th> <a href="updateschemetable.php?schemeid='.$schemeid.'">update</a></th>';
       echo '</tr>'; 
     }  	 
	 echo '</table>';
  }
  else
  {
    echo 'no record';  
  }
}
else
{
 echo'<br>'.mysqli_error($cn);
}
?>