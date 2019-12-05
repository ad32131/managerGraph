<?php
//--------------------------------------------------------------------
//  PREVIL Calendar
//
//  - calendar.php / lun2sil.php(open source)
//
//  - Programmed by previl(previl@hanmail.net, http://dev.previl.net)
//  
//--------------------------------------------------------------------
ini_set("memory_limit" , -1);
error_reporting(0);
date_default_timezone_set('Asia/Tokyo');
$c_path=".";
$cellh  = 100;  // date cell height
$tablew = 800; //table width
$three_count = 1;
$line_date = "";
$line_text1 = "";
$line_text2 = "";
//year=2019&month=11&date=5
if( (!empty($_GET['year']) && !empty($_GET['month']) && !empty($_GET['date']) )) $input_kdate = $_GET['year']."/".$_GET['month']."/".$_GET['date'];
include_once "query_set.php";
?>
 
<style>
.all { border-width:1; border-color:#cccccc; border-style:solid; }
font {font-family:굴림체; font-size: 12px; color:#505050;}
font.title {font-family: 굴림체; font-size: 12px; font-weight: bold; color:#2579CF;}
font.week {font-family:돋움,돋움체; color:#ffffff;font-size:8pt;letter-spacing:-1}
font.num {font-family:tahoma; font-size:20px;}
font.holy {font-family:tahoma; font-size:20px; color:#FF6C21;}
font.num2 {font-family:tahoma; font-size:14px; color:#bbbbbb;}
font.num3 {font-family:tahoma; font-size:14px; color:blue;}
font.num4 {font-family:tahoma; font-size:14px;} 
font.num5 {font-family:tahoma; font-size:14px; color:green;}
font.num6 {font-family:tahoma; font-size:14px; color:red;}
.main { float:left; width: 70%; border:5px solid #ccc; background-color:#fff; m }
.right { float:right; width: 20%; background-color:#fff; border:5px solid #eee; }

</style>
<script src="js/Chart.js"></script>
<div class = main>
<?php
//--------------------------------------------------------------------
//  FUNCTION
//--------------------------------------------------------------------
include "lun2sol.php";   //양음변환 인클루드
//include "schedule.php";





function ErrorMsg($msg)
{
  echo " <script>                ";
  echo "   window.alert('$msg'); ";
  echo "   history.go(-1);       ";
  echo " </script>               ";
  exit;
}

function SkipOffset($no,$sdate='',$edate='')
{  
  for($i=1;$i<=$no;$i++) { 
    $ck = $no-$i+1;
    if($sdate) $num = date('m/d',$sdate-(3600*24)*$ck);
	if($edate) $num = date('m/d',$edate+(3600*24)*($i-1)); 
	
    echo "  <TD valign=top><font class=num2>$num</font></TD> \n";	
  }
}



//---- 오늘 날짜
$thisyear  = date('Y');  // 2000
$thismonth = date('n');  // 1, 2, 3, ..., 12
$today     = date('j');  // 1, 2, 3, ..., 31

//------ $year, $month 값이 없으면 현재 날짜
if (empty($_GET['year'])) $year = $thisyear;
else $year = $_GET['year'];
if (empty($_GET['month']))  $month = $thismonth;
else $month = $_GET['month'];
if (empty($_GET['day']))   $day = $today;
else $day = $_GET['day'];
if (empty($_GET['neodate'])) $neodate=1;
else $neodate = $_GET['neodate'];


//------ 날짜의 범위 체크
if (($year > 2038) or ($year < 1900)) ErrorMsg("연도는 1900~2038년만 가능합니다.");
if (($month > 12) or ($month < 0)) ErrorMsg("달은 1~12만 가능합니다.");
/*
while (checkdate($month,$day,$year)): 
    $date++; 
endwhile; 
$maxdate = date-1;
*/
$maxdate = date('t', mktime(0, 0, 0, $month, 1, $year));   // the final date of $month

if ($day>$maxdate) ErrorMsg("$month 월 에는 $maxdate 일이 마지막 날입니다.");

$prevmonth = $month - 1;
$nextmonth = $month + 1;
$prevyear = $nextyear=$year;
if ($month == 1) {
  $prevmonth = 12;
  $prevyear = $year - 1;
} elseif ($month == 12) {
  $nextmonth = 1;
  $nextyear = $year + 1;
}
/****************** lunar_date ************************/
	$predate = date("Y-m-d", mktime(0, 0, 0, $month-1, 1, $year)); //속도를 위해 조회하는 전후 한달만 select
	$nextdate= date("Y-m-d", mktime(0, 0, 0, $month+1, 1, $year)); //속도를 위해 조회하는 전후 한달만 select


    $connect=mysqli_connect("localhost","linebot","F9fdGH9rr7E","linebot");
	$sql = "SELECT `solar_date`,`memo`,`ganji`,`lunar_date` FROM `g4_lunartosolar` where `solar_date` between '$predate' and '$nextdate' ";
	//$sql2 ="select mb_no as id, mb_no as subject, mb_no as contents, mb_date as writing_date, substr(mb_date,1,LOCATE(\"/\",mb_date)-1) AS start_year , SUBSTR(mb_date,   LOCATE(\"/\",mb_date) + 1 , ( LOCATE(\"/\",mb_date) + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) )    -   LOCATE(\"/\",mb_date)  - 1 ) ) AS start_month  , SUBSTR( mb_date,  LOCATE(\"/\",mb_date) + 1 + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) ) ) AS start_date, 0 AS end_year, 0 AS end_month, 0 AS end_date, \"Number\" AS selec_val, K AS etc  from (SELECT mb_no,mb_date,COUNT(*) AS K FROM stamp_date GROUP BY mb_no,mb_date ORDER BY K) AS RTX;";
    $sql2 ="select mb_no as id, mb_no as subject, mb_no as contents, mb_date as writing_date, substr(mb_date,1,LOCATE(\"/\",mb_date)-1) AS start_year , SUBSTR(mb_date,   LOCATE(\"/\",mb_date) + 1 , ( LOCATE(\"/\",mb_date) + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) )    -   LOCATE(\"/\",mb_date)  - 1 ) ) AS start_month  , SUBSTR( mb_date,  LOCATE(\"/\",mb_date) + 1 + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) ) ) AS start_date, 0 AS end_year, 0 AS end_month, 0 AS end_date, \"Number\" AS selec_val, K AS etc, COUNT(*) AS CO  from (SELECT mb_no,mb_date,COUNT(*) AS K FROM stamp_date GROUP BY mb_no,mb_date ORDER BY K) AS RTX GROUP BY writing_date ORDER BY writing_date;";
		
	$sql4 = "SELECT id,writing_date,COUNT(*) AS etc FROM (select mb_no as id, mb_no as subject, mb_no as contents, mb_date as writing_date, substr(mb_date,1,LOCATE(\"/\",mb_date)-1) AS start_year , SUBSTR(mb_date,   LOCATE(\"/\",mb_date) + 1 , ( LOCATE(\"/\",mb_date) + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) )    -   LOCATE(\"/\",mb_date)  - 1 ) ) AS start_month  , SUBSTR( mb_date,  LOCATE(\"/\",mb_date) + 1 + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) ) ) AS start_date, 0 AS end_year, 0 AS end_month, 0 AS end_date, \"Number\" AS selec_val, K AS etc from (SELECT mb_no,mb_date,COUNT(*) AS K FROM stamp_date GROUP BY mb_no,mb_date ORDER BY K) AS RTX HAVING etc>1 ORDER BY writing_date,etc ) AS TK GROUP BY writing_date;";
	$result=mysqli_query($connect,$sql);
	$result2=mysqli_query($connect,$sql2);
    $result4=mysqli_query($connect,$sql4);
	while ($array=mysqli_fetch_array($result)) {
		$schedule[] = array(0=>date("n-j", strtotime($array['solar_date'])), 1=>$array['ganji'], 2=>date("n-j", strtotime($array['lunar_date'])),3=>date("j", strtotime($array['lunar_date'])));
	}
/****************** lunar_date ************************/

/****************** schedule ************************/
	while ($array=mysqli_fetch_array($result2)) {
	    //$select_val = substr($array[$select_val],0, 8);
		$schedule1[] = array(0=>date("n-j", mktime(0,0,0,$array['start_month'],$array['start_date'],$array['start_year'])), 1=>$array['subject'], 2=>$array['contents'],3=>substr(trim($array['select_val']),0,3),4=>$array['id'],5=> date("n-j", mktime(0,0,0,$array['end_month'],$array['end_date'],$array['end_year'])),6=>$array['etc'],7=>$array['CO'],8=>$array['writing_date']  );
    }
/****************** schedule ************************/

while ($array=mysqli_fetch_array($result4)) {
    //$select_val = substr($array[$select_val],0, 8);
    $schedule2[] = array(0=>$array['id'], 1=>$array['writing_date'], 2=>$array['etc']);
}

/****************** 휴일 정의 ************************/
$HOLIDAY = Array();
$HOLIDAY[] = array(0=>'1-1',1=>'신정'); 
$HOLIDAY[] = array(0=>'3-1',1=>'삼일절');
//$HOLIDAY[] = array(0=>'4-5',1=>'식목일');
$HOLIDAY[] = array(0=>'5-5',1=>'어린이날');
$HOLIDAY[] = array(0=>'6-6',1=>'현충일');
$HOLIDAY[] = array(0=>'7-17',1=>'제헌절');
$HOLIDAY[] = array(0=>'8-15',1=>'광복절');
$HOLIDAY[] = array(0=>'10-3',1=>'개천절');

$HOLIDAY[] = array(0=>'12-25',1=>'성탄절');

$tmp = lun2sol($year."0101");   //설날
$HOLIDAY[] = array(0=>date("n-j",($tmp-(3600*24))),1=>'설날');
$HOLIDAY[] = array(0=>date("n-j",$tmp),1=>'설날');
$HOLIDAY[] = array(0=>date("n-j",($tmp+(3600*24))),1=>'설날');;

$tmp = lun2sol($year."0408");   //석탄일
$HOLIDAY[] = array(0=>date("n-j",$tmp),1=>'석탄일');

$tmp = lun2sol($year."0815");   //추석
$HOLIDAY[] = array(0=>date("n-j",($tmp-(3600*24))),1=>'추석');;
$HOLIDAY[] = array(0=>date("n-j",$tmp),1=>'추석');;
$HOLIDAY[] = array(0=>date("n-j",($tmp+(3600*24))),1=>'추석');;

unset($tmp);

/****************** 휴일 정의 ************************/
$time = time();
$year1 = date("Y", $time);
$month1 = date("m", $time);

// Style에서 띄어쓰면 안됨
echo("
<DIV align=center>
<TABLE cellSpacing=0 cellPadding=0 width=$tablew border=0>
	<TR>
		<TD width=1></TD><TD align=center>
			<TABLE cellSpacing=0 cellPadding=0 width=90% border=0 height=11>  
			<TR>
				<TD width=1%></TD>
				<TD width=14% align=center valign=bottom><img src='$c_path/img/box_top_line1.gif' width=5 height=3>
				</TD>
				<TD width=14% align=center valign=bottom><img src='$c_path/img/box_top_line1.gif' width=5 height=3>
				</TD>
				<TD width=14% align=center valign=bottom><img src='$c_path/img/box_top_line1.gif' width=5 height=3>
				</TD>
				<TD width=14% align=center valign=bottom><img src='$c_path/img/box_top_line1.gif' width=5 height=3>
				</TD>
				<TD width=14% align=center valign=bottom><img src='$c_path/img/box_top_line1.gif' width=5 height=3>
				</TD>
				<TD width=14% align=center valign=bottom><img src='$c_path/img/box_top_line1.gif' width=5 height=3>
				</TD>
				<TD width=14% align=center valign=bottom><img src='$c_path/img/box_top_line1.gif' width=5 height=3>
				</TD>
				<TD width=1%></TD>
			</TR>
			</TABLE>
		</TD>
			<TD width=1>
			</TD>
	</TR>
</TABLE>

<TABLE cellSpacing=0 cellPadding=0 width=$tablew border=0 class=all>
	<TR>
		<TD height=13 background='$c_path/img/box_top_bg.gif' align=center>
			<TABLE cellSpacing=0 cellPadding=0 width=90% border=0 height=13>  
			<TR>
				<TD width=1%></TD>  
				<TD width=14% align=center valign=top><img src='$c_path/img/box_top_line2.gif' width=5 height=7>
				</TD>
				<TD width=14% align=center valign=top><img src='$c_path/img/box_top_line2.gif' width=5 height=7>
				</TD>
				<TD width=14% align=center valign=top><img src='$c_path/img/box_top_line2.gif' width=5 height=7>
				</TD>
				<TD width=14% align=center valign=top><img src='$c_path/img/box_top_line2.gif' width=5 height=7>
				</TD>
				<TD width=14% align=center valign=top><img src='$c_path/img/box_top_line2.gif' width=5 height=7>
				</TD>
				<TD width=14% align=center valign=top><img src='$c_path/img/box_top_line2.gif' width=5 height=7>
				</TD>
				<TD width=14% align=center valign=top><img src='$c_path/img/box_top_line2.gif' width=5 height=7>
				</TD>
				<TD width=1%>
				</TD>
			</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD align=center>
			<TABLE cellSpacing=0 cellPadding=0 width=90% border=0>
				<TR><TD height=3></TD></TR>   
				<TR><TD height=1 colspan=3 bgcolor=efefef></TD></TR>
				<TR><TD height=3></TD></TR>
				<TR><TD width=15% align=right>
					<a href=$_SERVER[PHP_SELF]?year=$prevyear&month=$prevmonth&day=1 onfocus='this.blur()'>
					<img src='$c_path/img/back.png' border=0 onfocus='this.blur();' align=right width=20 height=20></a>        
				</TD>
			<TD width=10% align=center>
				<font class=title>{$year}년 {$month}월</font>
            </TD>
			<TD width=15% align=left>
				<a href=$_SERVER[PHP_SELF]?year=$nextyear&month=$nextmonth&day=$day onfocus='this.blur()'>
				<img src='$c_path/img/next.png' border=0 onfocus='this.blur();' align=left width=20 height=20></a>
	        </TD>
			<td width = 7%>
			<a href=$_SERVER[PHP_SELF]?year=$year1&month=$month1&day=$day onfocus='this.blur()'>
			<img src='$c_path/img/today.png' border=0 onfocus='this.blur();' align=left width=40 height=20></a>
			
		</TR>		
		</TABLE>
    </TD>
</TR>
<TR><TD height=3></TD></TR>
<TR><TD align=center>
        <TABLE cellSpacing=0 cellPadding=0 width=90% border=1>  
		<TR>
		    <TD bgcolor=#68AFF7><TABLE cellSpacing=0 cellPadding=0 width=1 height=1 border=0>
				<TR><TD bgcolor=ffffff></TD></TR></TABLE></TD>
		    <TD colspan=7 bgcolor=#68AFF7 height=1></TD>
			<TD bgcolor=#68AFF7 align=right><TABLE cellSpacing=0 cellPadding=0 width=1 height=1 order=0>
				<TR><TD bgcolor=ffffff></TD></TR>
		</TABLE>
	</TD>
</TR>
<TR><TD colspan=9 bgcolor=#68AFF7 height=3></TD></TR>
	<TR bgcolor=#68AFF7>
	    <TD width=1%></TD>
		<TD width=14% align=center><font class=week>일</font></TD>            
		<TD width=14% align=center><font class=week>월</font></TD>
		<TD width=14% align=center><font class=week>화</font></TD>
		<TD width=14% align=center><font class=week>수</font></TD>
		<TD width=14% align=center><font class=week>목</font></TD>
		<TD width=14% align=center><font class=week>금</font></TD>
		<TD width=14% align=center><font class=week>토</font></TD>
		<TD width=1%></TD>
    </TR>
	<TR>
		<TD colspan=9 bgcolor=#68AFF7 height=1></TD></TR>
	<TR>
		<TD bgcolor=#68AFF7>
			<TABLE cellSpacing=0 cellPadding=0 width=1 height=1 border=0>
				<TR><TD bgcolor=ffffff></TD></TR>
			</TABLE></TD>
		<TD colspan=7 bgcolor=#68AFF7 height=1></TD>
		<TD bgcolor=#68AFF7 align=right>
			<TABLE cellSpacing=0 cellPadding=0 width=1 height=1 order=0>
				<TR><TD bgcolor=ffffff></TD></TR>
			</TABLE>
		</TD>
	</TR>
");

echo("
		<TR height=$cellh><TD></TD>
        <!-- 날짜 테이블 -->
");

$date   = 1;
$offset = 0;
$ck_row=0; //프레임 사이즈 조절을 위한 체크인자
$print = array();

while ($date <= $maxdate) {
   if ($date < 10) $date2 = "&nbsp;".$date;
   else $date2 = $date;
  if($date == '1') {
    $offset = date('w', mktime(0, 0, 0, $month, $date, $year));  // 0: sunday, 1: monday, ..., 6: saturday
    SkipOffset($offset,mktime(0, 0, 0, $month, $date, $year));
   }
   if($offset == 0) $style ="holy";
   else if($offset == 6) $style ="num3";
   else $style = "num";
   
   for($i=0;$i<count($HOLIDAY);$i++){	   
       if($HOLIDAY[$i][0] =="$month-$date") {
           /*
		   $style="holy"; 
		   $holy_text = $HOLIDAY[$i][1];
		   //$date2 = "<font title='{$month}월 {$date}일은 ".$holy_text." 입니다' class='$style' style=cursor:point>$date2 </br> $holy_text</font>";    
		   $date2 = "$date2 <nbsp;> $holy_text";  
		   //$print = array(0=>$date, 1=>$holy_text);
           */

		   break;
       }	   
   }
   for($i=0;$i<count($schedule);$i++){
       if($schedule[$i][0] =="$month-$date") {
		   $lunar_text = $schedule[$i][2];

		   //$date2 =  "<font title='".$schedule[$i][7]."' class='num4' style=cursor:point>[".$schedule[$i][7]."]:".$schedule2[$i][2]."</font></br>";;
           /*
		   switch($schedule[$i][3])
			{
				case 1: 
					$date3 = "(음:$lunar_text)</br>";  
					//$print[3] = array(
				break;
				
				case 15:
					$date3 = "(음:$lunar_text)</br>";
				break;	
		    
		   }
		   */
		   break;
       }	   
   }
   for($i=0;$i<count($schedule1);$i++){
       if($schedule1[$i][0] =="$month-$date") {
		   $select = $schedule1[$i][3];
		   $subject = $schedule1[$i][1];
		   //$id = $schedule1[$i][4];
		   $etc = $schedule1[$i][6];
		   /*
		  switch ($etc) {
		   	case 1:
                $date4 = "<font title='".$schedule1[$i][7]."' class='num4' style=cursor:point>[".$schedule1[$i][7]."]:".$schedule2[$i][2]."</font></br>";
                $line_date  = $line_date."\"".$schedule1[$i][0]."\",";
                $line_text1 =  $line_text1."".$schedule1[$i][7].",";
                $line_text2 =  $line_text2."".$schedule2[$i][2].",";
                $id = $schedule1[$i][4];
		   	break;
		   	
		   	case 2:
                $date4 = "<font title='".$schedule1[$i][7]."' class='num4' style=cursor:point>[".$schedule1[$i][7]."]:".$schedule2[$i][2]."</font></br>";
                $line_date  = $line_date."\"".$schedule1[$i][0]."\",";
                $line_text1 =  $line_text1."".$schedule1[$i][7].",";
                $line_text2 =  $line_text2."".$schedule2[$i][2].",";
                $id = $schedule1[$i][4];
		  	break;
		  }
		  */


              $date4 = "<font title='".$schedule1[$i][7]."' class='num4' style=cursor:point>[".$schedule1[$i][7]."]:".$schedule2[$i][2]."</font></br>";
           $line_date  = $line_date."\"".$schedule1[$i][0]."\",";
           $line_text1 =  $line_text1."".$schedule1[$i][7].",";
           $line_text2 =  $line_text2."".$schedule2[$i][2].",";
			$id = $schedule1[$i][4];


       }
     }
	 /*
	 for($i=0;$i<count($schedule1);$i++){
		 if($schedule1[$i][0] =="$month-$date") {
		   $select = $schedule1[$i][3];
		   $subject = $schedule1[$i][1];
		   $id1 = $schedule1[$i][4];
		   $etc = $schedule1[$i][6];

		   switch ($etc) {
		   	case 1:
		   	$date4 = "<font title='".$schedule1[$i][2]."' class='num4' style=cursor:point>[$select]$subject</font></br>";
		   	break;

		   	case 2:
		   		$date5 = "<font title='".$schedule1[$i][2]."' class='num5' style=cursor:point>[$select]$subject</font></br>";
		  	break;
		   }



		  if($etc ==2){
			$date5 = "<font title='".$schedule1[$i][2]."' class='num5' style=cursor:point>[$select]$subject</font></br>";
		  }

		}
		}
  	*/
   if($offset!==0 ){
   $print[] = array(0=>$date, 1=>$holy_text, 2=>$date3 , 3=>$date4, 4=>$date5, 5=>$date6);
   }
   
    //var_dump ($three_count < 3);
   if ( $date == $today  &&  $year == $thisyear &&  $month == $thismonth) {
    
   echo "<TD valign=top bgcolor=#99FFFF ><font class=$style>$date2</font><font class=num4><a href='calendar.php?year=$year&month=$month&date=$date&two=two'>$date4</a></font></br></TD> \n";
   }
   //else if(empty($_GET['date'] && empty($_GET['year']) && ($_GET['month']))){
   else if ( $date == $_GET['date']  &&  $year == $_GET['year'] &&  $month == $_GET['month']) {

       if ($three_count === 2 ){
           echo "<TD valign=top bgcolor=#FFC870 ><font class=$style>$date2</font></br><font class=num4><a href='calendar.php?year=$year&month=$month&date=$date&two=two'>$date4</a></font></TD> \n";
           if( !empty($date4)) $three_count++;
       }
        else if( $three_count < 3){
            echo "<TD valign=top bgcolor=#FFC870 ><font class=$style>$date2</font></br><font class=num4>$date4</font></TD> \n";
            if( !empty($date4)) $three_count++;
        }
        else {
            echo "<TD valign=top bgcolor=#FFC870 ><font class=$style>$date2</font></br><font class=num4><a href='calendar.php?year=$year&month=$month&date=$date'>$date4</a></font></TD> \n";

        }
   //}

   }
   else {

       if ($three_count === 2 ){
           echo "<TD valign=top><font class=$style >$date2</font></br><font class=num3>$date3</font><font class=num4><a href='calendar.php?year=$year&month=$month&date=$date&two=two'>$date4</a></font><font class=num5>$date5</font><font class=num6></font></TD> \n";
           if( !empty($date4)) $three_count++;
       }else if($three_count < 3){
           echo "<TD valign=top><font class=$style >$date2</font></br><font class=num3>$date3</font><font class=num4>$date4</font><font class=num5>$date5</font><font class=num6></font></TD> \n";
           if( !empty($date4)) $three_count++;
       }
       else{
           echo "<TD valign=top><font class=$style >$date2</font></br><font class=num3>$date3</font><font class=num4><a href='calendar.php?year=$year&month=$month&date=$date'>$date4</a></font><font class=num5>$date5</font><font class=num6></font></TD> \n";

       }

   }

	$date3="";
	$date4 = "";
	$date5 ="";
	$date6="";
	$date++;
	$offset++;
    $holy_text = ""; 
  if ($offset == 7) {
    echo "<TD></TD></TR> \n";
    if ($date <= $maxdate) {
      echo "<TR height=$cellh><TD valign=top></TD>\n";
	  $ck_row++;
    }
    $offset = 0;
  }

} // end of while

if ($offset != 0) {
  SkipOffset((7-$offset),'',mktime(0, 0, 0, $month+1, 1, $year));
  echo "<TD></TD></TR> \n";
}
echo("
<!-- 날짜 테이블 끝 -->
        </TD>
     </TR>
	 </TABLE>
<TR><TD height=3></TD></TR>
</TABLE>
</DIV>
") ;

?>

<div align = center >
<?php
echo "</br>";
echo "</br>";

//foreach($print as $value) echo $value." , ";
?>
<div style="display: inline">

    <div style="width:30%;display: inline-block">
        <p>User</p>
        <div>
            <canvas id="canvas" height="450" width="600"></canvas>
        </div>
    </div>
    <div style="width:30%;display: inline-block">
        <p>overlap</p>
        <div>
            <canvas id="canvas2" height="450" width="600"></canvas>
        </div>
    </div>
</div>
</div>
</div>

<div class = right>

<?php
echo "</br>";
 echo "<table border = 1><tr><td width  = 40>DATE</td>";
 echo "<td width  = 100>MEMBER</td></tr>";
$total_record2 = mysqli_num_rows($result2);
/*
for($i=0;$i<count($print);$i++) {
if(!$print[$i][3] =="" or !$print[$i][4] =="" or !$print[$i][5] ==""){
  
    echo "<tr><td>"; 
	echo $print[$i][0] ."</td><td >"; 

	echo $print[$i][3].$print[$i][4].$print[$i][5]   ;
	echo "</td></tr>";

}
}
*/
/*
$sql3 = "SELECT id,writing_date,etc FROM (select mb_no as id, mb_no as subject, mb_no as contents, mb_date as writing_date, substr(mb_date,1,LOCATE(\"/\",mb_date)-1) AS start_year , SUBSTR(mb_date,   LOCATE(\"/\",mb_date) + 1 , ( LOCATE(\"/\",mb_date) + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) )    -   LOCATE(\"/\",mb_date)  - 1 ) ) AS start_month  , SUBSTR( mb_date,  LOCATE(\"/\",mb_date) + 1 + LOCATE( \"/\" , SUBSTR( mb_date , LOCATE(\"/\",mb_date) +1 ) ) ) AS start_date, 0 AS end_year, 0 AS end_month, 0 AS end_date, \"Number\" AS selec_val, K AS etc from (SELECT mb_no,mb_date,COUNT(*) AS K FROM stamp_date GROUP BY mb_no,mb_date ORDER BY K) AS RTX HAVING etc>1 ORDER BY writing_date,etc ) AS TK order BY writing_date;";
$result3 = mysqli_query($connect, $sql3);
$total_record3 = mysqli_num_rows($result3);
$view_idx = "";
*/

$sqltime = "(SELECT MAX( CAST( mb_write_date AS DATE) ) AS times FROM stamp_date);";
$resulttime = mysqli_query($connect, $sqltime);
$total_recordtime = mysqli_num_rows($resulttime);
mysqli_data_seek($resulttime, 0);
$row = mysqli_fetch_array($resulttime);
if( !empty($input_kdate) ){
    $release_data_date = $input_kdate;
}else{
    $release_data_date = $row['times'];
}
//$input_kdate

//$sql3 = "SELECT COUNT(*) AS times FROM (SELECT A.* FROM (SELECT A.* from (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date))A LEFT JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))B ON A.mb_no = B.mb_no WHERE B.mb_no IS NULL)A LEFT JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) < (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))C ON A.mb_no = C.mb_no WHERE C.mb_no IS NULL)A;";
$result3 = mysqli_query($connect, query_setting($input_kdate,"new"));
$total_record3 = mysqli_num_rows($result3);
$view_idx = "";

//echo "<tr>";
echo "NEW";
    for ($i=0; $i < $total_record3; $i++)
    {
        mysqli_data_seek($result3, $i);
        $row = mysqli_fetch_array($result3);
        echo "<tr><td>".$release_data_date ."</td>";

        echo "<td>";
        //if($row[etc] > 1) echo "<font title=\"$release_data_date\" class=\"num6\" style=\"cursor:point\">";
        echo "<font title=\"$row[times]\" class=\"num4\" style=\"cursor:point\">";
        echo $row['times'];
        $new = $row['times'];
        echo "</font>";
        echo "</td>";
        echo "</tr>";
    }

echo "</table>";
    ?>

<?php
//$sql4 = "SELECT COUNT(*) AS times FROM (SELECT A.* FROM (SELECT A.* FROM (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date))A INNER JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) < (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))C ON A.mb_no = C.mb_no)A LEFT JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))B on A.mb_no = B.mb_no WHERE B.mb_no IS NULL GROUP BY mb_no)A;";
$result4 = mysqli_query($connect, query_setting($input_kdate,"return"));
$total_record4 = mysqli_num_rows($result4);
$view_idx = "";

echo "</br>";
echo "<table border = 1><tr><td width  = 40>DATE</td>";
echo "<td width  = 100>MEMBER</td></tr>";
echo "Return User";
for ($i=0; $i < $total_record4; $i++)
{
    mysqli_data_seek($result4, $i);
    $row = mysqli_fetch_array($result4);
    echo "<tr><td>".$release_data_date ."</td>";

    echo "<td>";
    //if($row[etc] > 1) echo "<font title=\"$release_data_date\" class=\"num6\" style=\"cursor:point\">";
    echo "<font title=\"$row[times]\" class=\"num4\" style=\"cursor:point\">";
    echo $row['times'];
    $Return = $row['times'];
    echo "</font>";
    echo "</td>";
    echo "</tr>";
}
//echo "</tr>";


echo "</table>";

?>


<?php
//$sql5 = "SELECT COUNT(*) as times FROM (SELECT A.* FROM (SELECT A.* FROM (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date))A INNER JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))B ON A.mb_no = B.mb_no)A INNER JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) < (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))C ON A.mb_no = C.mb_no GROUP BY mb_no)A;";
$result5 = mysqli_query($connect, query_setting($input_kdate,"Current"));
$total_record5 = mysqli_num_rows($result5);
$view_idx = "";
echo "</br>";
echo "<table border = 1><tr><td width  = 40>DATE</td>";
echo "<td width  = 100>MEMBER</td></tr>";
echo "Current User";
for ($i=0; $i < $total_record5; $i++)
{
    mysqli_data_seek($result5, $i);
    $row = mysqli_fetch_array($result5);
    echo "<tr><td>".$release_data_date ."</td>";

    echo "<td>";
    //if($row[etc] > 1) echo "<font title=\"$release_data_date\" class=\"num6\" style=\"cursor:point\">";
    echo "<font title=\"$row[date]\" class=\"num4\" style=\"cursor:point\">";
    echo $row['times'];
    $Current = $row['times'];
    echo "</font>";
    echo "</td>";
    echo "</tr>";
}
//echo "</tr>";


echo "</table>";

?>


<?php
//$sql6 = "SELECT COUNT(*) AS times FROM (SELECT B.* FROM (SELECT B.* FROM (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))B INNER JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) < (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date)))C ON B.mb_no =  C.mb_no)B LEFT JOIN (SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT MAX( CAST( mb_write_date AS DATE) ) FROM stamp_date))A ON B.mb_no = A.mb_no WHERE A.mb_no IS NULL)B;";
$result6 = mysqli_query($connect, query_setting($input_kdate,"exit"));
$total_record6 = mysqli_num_rows($result6);
$view_idx = "";
echo "</br>";
echo "<table border = 1><tr><td width  = 40>DATE</td>";
echo "<td width  = 100>MEMBER</td></tr>";
echo "User Exit";
for ($i=0; $i < $total_record6; $i++)
{
    mysqli_data_seek($result6, $i);
    $row = mysqli_fetch_array($result6);
    echo "<tr><td>".$release_data_date ."</td>";

    echo "<td>";
    //if($row[etc] > 1) echo "<font title=\"$release_data_date\" class=\"num6\" style=\"cursor:point\">";
    echo "<font title=\"$row[date]\" class=\"num4\" style=\"cursor:point\">";
    echo $row['times'];
    $Exit = $row['times'];
    echo "</font>";
    echo "</td>";
    echo "</tr>";
}
//echo "</tr>";


echo "</table>";

?>
    <div id="canvas-holder" style="width:100%">
        compare
        <canvas id="chart-area" width="800" height="800"/>
    </div>


    <script>
        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            ctx.fillRect(10, 10, 20, 20);

            var ctx2 = document.getElementById("canvas2").getContext("2d");
            ctx2.fillRect(10, 10, 20, 20);

            var ctx3 = document.getElementById("chart-area").getContext("2d");
            ctx3.fillRect(10, 10, 20, 20);
        };

        var lineChartData = {
            labels : [<?php echo substr($line_date , 0, -1); ?>],
            datasets : [
                {
                    label: "user",
                    fillColor : "rgba(80,80,220,0.2)",
                    strokeColor : "rgba(220,120,120,0.9)",
                    pointColor : "rgba(30,230,220,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(220,220,220,1)",
                    data : [<?php echo substr($line_text1 , 0, -1); ?>]
                }
            ]

        }

        var lineChartData2 = {
            labels : [<?php echo substr($line_date , 0, -1); ?>],
            datasets : [
                {
                    label: "overlap",
                    fillColor : "rgba(220,220,80,0.2)",
                    strokeColor : "rgba(220,220,80,0.9)",
                    pointColor : "rgba(80,120,30,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(220,220,220,1)",
                    data : [<?php echo substr($line_text2 , 0, -1); ?>]
                }
            ]

        }

        var polarData = [
            {
                value: <?php echo $new; ?>,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "NEW"
            },
            {
                value: <?php echo $Return; ?>,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Return User"
            },
            {
                value: <?php echo $Current; ?>,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Current User"
            },
            {
                value: <?php echo $Exit; ?>,
                color: "#949FB1",
                highlight: "#A8B3C5",
                label: "User Exit"
            }

        ];

        window.onload = function(){
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx).Line(lineChartData, {
                responsive: true
            });
            var ctx2 = document.getElementById("canvas2").getContext("2d");
            window.myLine = new Chart(ctx2).Line(lineChartData2, {
                responsive: true
            });


            var ctx3 = document.getElementById("chart-area").getContext("2d");
            window.myPolarArea = new Chart(ctx3).PolarArea(polarData, {
                responsive:true
            });
        }



    </script>


</div>
<div>


</div>

