<?php
$m=$ms="";
function messagecheck()
{
  $m=trim($_POST['m']);
  if(strlen($m)>=4)
  { 
    return'y';
  }
  else
  {
    return'n';
  }
}
if(isset($_POST['s']))
{
if(messagecheck()=='y')
  {
    $m=trim($_POST['m']);
  }
  else
  {
    $ms="**** check message ";
  }
  if(messagecheck()=='y')
{
  $m="message".$m;
  echo $m;
}
else
{
  echo '<br> check';
}  
}
?>
<form action=""method="POST"enctype="multipart/form-date">
message:<input type="text" name="m" value="<?php echo $m;?>">
<span style="color:red;"><?php echo $ms;?></span>
<br><br>
<input type="submit" name="s">
<input type="submit" name="s1" value="clear">
</form>