<?php
    date_default_timezone_set('Asia/Tokyo');
    $connect=mysqli_connect("localhost","linebot","F9fdGH9rr7E","linebot");
	
    $id=$_GET['id'];

    $sql ="select * from schedular where `id`=$id" ;
	
	$result=mysqli_query($connect,$sql);
	
	while ($array=mysqli_fetch_array($result)) {
	
	 
	$subject = $array['subject'];
	$start_year = $array['start_year'];
	$start_month = $array['start_month'];
	$start_date = $array['start_date'];
	$select_val = $array['select_val'];
	/*
	switch($select_val)
			{
				case "휴": 
					$select_val = "정기휴가";					
				break;
				
				case "청":
					$select_val = "청원휴가";	
				break;	
				
				case "교":
					$select_val = "교육";	
				break;	
				
				case "출":
					$select_val = "출장";	
				break;	
				
				case "기타":
					$select_val = "기타사유"	;
				break;	
		   }
    */
	
	$contents = $array['contents'];
	
	}
	
?>


 <form name = "read" method="" action="calendar.php">
<CENTER>

<br>
<h1>스케쥴 확인</h1>
<hr>

<TABLE>
<TR>
 <TD>이름 : </TD>
 <TD> <? echo $subject; ?> 
  </TD>
</TR>
<TR>
 <TD>시작날짜 : </TD>
 <td> <? echo $start_year ."년 ",  $start_month."월 ", $start_date."일" ; ?> 
  </td> 
</TR>
<tr>
 <td> 휴가 종류 :  </td> 
 <TD> <? echo $select_val; ?> 
 </td>
</tr>
<TR>
 <TD>내용 : </TD>
 <TD> <? echo $contents; ?> 
 </TD>
</TR>
</TABLE>
<hr>
<INPUT TYPE="submit" value = "확인" >

</CENTER> 
</form>
<form name = "del" method="POST" action ="delete.php?id=<? echo $id; ?>">
<CENTER>
<INPUT TYPE="submit" value = "삭제" >	
</CENTER> 
</form>


