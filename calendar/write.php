
<form name = "write" method="post" action="writing.php">
<CENTER>

<br>
<h1>스케쥴 작성</h1>
<hr>

<TABLE border = 1>
<TR>
 <TD>이름</TD>
 <TD><INPUT TYPE="text" NAME="subject">
  </TD>
 <td>
  <input type="radio" name="etc" value="1">약사직
  <input type="radio" name="etc" value="2">일반직
  <input type="radio" name="etc" value="3">기타
</td>
</TR>
<TR>
 <TD >시작날짜</TD>
 <TD colspan="2" rowspan="1"><select name="start_date1">
<?
//---- 오늘 날짜
$thisyear  = date('Y');  // 2000
$thismonth = date('n');  // 1, 2, 3, ..., 12
$today     = date('j');  // 1, 2, 3, ..., 31

//------ $year, $month 값이 없으면 현재 날짜
if (!$year=$HTTP_GET_VARS['year']) $year = $thisyear;
if (!$month=$HTTP_GET_VARS['month']) $month = $thismonth;
if (!$day=$HTTP_GET_VARS['day']) $day = $today;

for ($i = 2007; $i < 2038; $i++) {
?>
 <option value="<? echo $i; ?>"><? echo $i; ?>년</option>
<?
}
?>
 <option selected value ="<? echo $year; ?>"><? echo $year; ?>년</option>
 </select>
  <select name="start_date2" >
<?
for ($i = 1; $i < 13; $i++) {
?>
 <option value="<? echo $i; ?>"><? echo $i; ?>월</option>
<?
}
?>
   <option selected value ="<? echo $month; ?>"><? echo $month; ?>월</option>
   </select>
    <select name="start_date3" >
<?
for ($i = 1; $i < 32; $i++) {
?>
 <option value="<? echo $i; ?>"><? echo $i; ?>일</option>
<?
}
?>
  <option selected value ="<? echo $day; ?>"><? echo $day; ?>일</option>
  </select>
  </TD>
</TR>
<TD>종료날짜</TD>
 <TD colspan="2" rowspan="1"><select name="end_date1">
<?
//---- 오늘 날짜
$thisyear  = date('Y');  // 2000
$thismonth = date('n');  // 1, 2, 3, ..., 12
$today     = date('j');  // 1, 2, 3, ..., 31

//------ $year, $month 값이 없으면 현재 날짜
if (!$year=$HTTP_GET_VARS['year']) $year = $thisyear;
if (!$month=$HTTP_GET_VARS['month']) $month = $thismonth;
if (!$day=$HTTP_GET_VARS['day']) $day = $today;

for ($i = 2007; $i < 2038; $i++) {
?>
 <option value="<? echo $i; ?>"><? echo $i; ?>년</option>
<?
}
?>
 <option selected value ="<? echo $year; ?>"><? echo $year; ?>년</option>
 </select>
  <select name="end_date2" >
<?
for ($i = 1; $i < 13; $i++) {
?>
 <option value="<? echo $i; ?>"><? echo $i; ?>월</option>
<?
}
?>
   <option selected value ="<? echo $month; ?>"><? echo $month; ?>월</option>
   </select>
    <select name="end_date3" >
<?
for ($i = 1; $i < 32; $i++) {
?>
 <option value="<? echo $i; ?>"><? echo $i; ?>일</option>
<?
}
?>
  <option selected value ="<? echo $day; ?>"><? echo $day; ?>일</option>
  </select>
  </TD>
</TR>
<tr>
 <td> 휴가 종류 </td> 
 <TD colspan="2" rowspan="1"><select name="select_val">
  <option value="휴가(정기)">정기휴가</option>
   <option value="휴가(년차)">휴가</option>
  <option value="청원휴가">청원휴가</option>   
  <option value="교육">전일교육</option>
  <option value="교육(오후)">반일교육</option>
  <option value="출장">출장</option>
  <option value="출장(오후)">출장</option>
  <option value="etc">기타사유</option>
  </select>
 </td>
</tr>
<TR>
 <TD>내용</TD>
 <TD colspan="2" rowspan="1"><TEXTAREA NAME="contents" ROWS="10" COLS="42"></TEXTAREA></TD>
</TR>
</TABLE>
<hr>
<INPUT TYPE="button" value = "입력" onclick = "Check()">
<INPUT TYPE="reset" value = "재입력" >
</CENTER>
</form>


<script language = "javascript">



function Check(){


if(write.start_date1.value==""){
alert("시작년도를 입력하세요");
write.start_date1.focus();
return;
}
if(write.start_date2.value==""){
alert("시작월을 입력하세요");
write.start_date2.focus();
return;
}
if(write.start_date3.value==""){
alert("시작날짜를 입력하세요");
write.start_date3.focus();
return;
}
if(write.contents.value==""){
alert("내용을 입력하세요");
write.contents.focus();
return;
}

var start_date = new Date(write.start_date1.value, write.start_date2.value, write.start_date3.value);
var end_date = new Date(write.end_date1.value, write.end_date2.value, write.end_date3.value);




if((end_date - start_date) < 0){
alert("종료일이 시작일보다 빠른 날짜입니다");
write.end_date1.focus();
return;
}

write.submit();
}


</script>
