<?php
function query_setting($input_kdate,$type){
    if( !empty($input_kdate) ){
        $offset_time  = $input_kdate;
        $today_A = "SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = '$offset_time'";
        $lastday_B = "SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  '$offset_time')";
        $ago_C = "SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) < (SELECT MAX( CAST(mb_write_date AS DATE )) FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  '$offset_time'))";

    }else{
        $offset_time = "(SELECT MAX( CAST( mb_write_date AS DATE) ) AS times FROM stamp_date)";
        $today_A = "SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = $offset_time";
        $lastday_B = "SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  $offset_time)";
        $ago_C = "SELECT * FROM stamp_date WHERE CAST( mb_write_date AS DATE) < (SELECT MAX( CAST(mb_write_date AS DATE )) FROM stamp_date WHERE CAST( mb_write_date AS DATE) = (SELECT  MAX(CAST( mb_write_date AS DATE))  FROM stamp_date WHERE CAST( mb_write_date AS DATE)  <  $offset_time))";

    }

    if( $type === "A"){
        return $today_A;
    }
    else if ($type == "B"){
        return $lastday_B;
    }
    elseif ($type === "C"){
        return $ago_C;
    }
    else if($type === "new"){
        $AminorB = "SELECT A.* FROM ($today_A)A LEFT JOIN ($lastday_B)B ON A.mb_no = B.mb_no where B.mb_no IS NULL ";
        $new = "SELECT A.* FROM ($AminorB)A LEFT JOIN ($ago_C)C ON A.mb_no = C.mb_no where C.mb_no IS NULL";
        $new_total = "select count(*) as times from ($new group by mb_no)A";
        return $new_total;
    }
    else if($type === "return"){
        $AinnerC = "SELECT A.* FROM ($today_A)A INNER JOIN ($ago_C)C ON A.mb_no = C.mb_no";
        $return = "SELECT A.* FROM ($AinnerC)A LEFT JOIN ($lastday_B)B ON A.mb_no = B.mb_no where B.mb_no IS NULL ";
        $return_total = "select count(*) as times from ($return group by mb_no)A";
        return $return_total;
    }
    else if($type === "Current"){
        $AinnerB = "SELECT A.* FROM ($today_A)A INNER JOIN ($lastday_B)B ON A.mb_no = B.mb_no";
        $Current = "SELECT A.* FROM ($AinnerB)A INNER JOIN ($ago_C)C ON A.mb_no = C.mb_no";
        $Current_total = "select count(*) as times from ($Current group by mb_no)A";
        return $Current_total;
    }
    else if($type === "exit"){
        $BinnerC = "SELECT B.* FROM ($lastday_B)B INNER JOIN ($ago_C)C ON B.mb_no = C.mb_no";
        $exit = "SELECT B.* FROM ($BinnerC)B LEFT JOIN ($today_A)A ON B.mb_no = A.mb_no where A.mb_no IS NULL ";
        $exit_total = "select count(*) as times from ($exit group by mb_no)B";
        return $exit_total;
    }
}

?>