<?php
//$now = time();
//$today = date('Y-m-i', $now);      
$curmonth = $_GET['month'];
$curyear = $_GET['year'];

$tomorrow  = mktime (0,0,0,date("m")+1  , date("i"), date("Y"));
$lastmonth = mktime (0,0,0,date("m",$today)-1, date("i",$today),   date("Y",$today));

$lastmn = date("Y-m-i",$tomorrow  );

 
 if ($curmonth == "" || $curyear == ""){
  $curmonth = date('m');
  $curyear = date('Y');
 }

 //$curyear = date('Y');
//$curmonth = date('m');

$date_date = date('j');
$date_month_date = date('t', mktime(0, 0, 0, $curmonth, 1, $curyear));
$date_week = date('w', mktime(0, 0, 0, $curmonth, 1, $curyear));
$column = 0;
   
    //$where = "where `solar_date` = $today";
	
	$today = time();
	
	$predate = date("Y-m-d",strtotime("-30 day", $today));
	$nextdate= date("Y-m-d",strtotime("+30 day", $today));

	$connect=mysqli_connect("localhost","linebot","F9fdGH9rr7E","linebot");
	$sql = "SELECT `solar_date`,`memo`,`ganji` FROM `g4_lunartosolar` where `solar_date` between '$predate' and '$nextdate' ";
	//$sql = "SELECT `solar_date`, `memo` FROM `g4_lunartosolar` $where";
  	
	$result=mysqli_query($connect,$sql) or die (mysqli_error());
	
	while ($array1=mysqli_fetch_array($result)) {
	//$HOLIDAY[] = array(0=>date("n-j",($tmp-(3600*24))),1=>'설연휴');
	    
	$schedule = array();
	$schedule[] = array(0=>date("n-j",$array1[solar_date]), 1=>$array1[ganji]);
	
	}
	
	
?>
	