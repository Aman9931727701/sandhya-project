<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	

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
$q="select * from apply";
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
				echo '<br> apply id: '.$r['aid'];
				echo '<br>teacher id: '.$r['tid'];
				echo '<br> student id: '.$r['sid'];
				echo '<br> course id: '.$r['coid'];
				echo ' <br>start date: '.$r['startdate'];
				echo ' <br>end date: '.$r['enddate'];
				echo ' <br>amount:'.$r['amount'];
				echo ' <br>date: '.$r['adate'];
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

</div>
</div>