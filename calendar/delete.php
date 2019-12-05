<?php
$connect=mysqli_connect("localhost","linebot","F9fdGH9rr7E","linebot");

$id=$_GET['id'];
$etc = "4";
    
   //$sql ="UPDATE `one`.`schedular` SET `etc` = '4' where `id`=$id";
	//$sql = "UPDATE `one`.`schedular` SET `etc` = '4' WHERE `schedular`.`id` = $id";
   $sql = "UPDATE `one`.`schedular` SET `etc` = '$etc' WHERE `schedular`.`id` = '$id'";	
	$result=mysqli_query($sql,$connect) or die (mysqli_error());
	
	
	//echo $id."</br>";
	//echo $etc;
    header("location:year_schedule");	
?>

